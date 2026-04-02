<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\User;
use App\Models\Setting;
use App\Models\DeliveryInterval;
use App\Models\Coupon;                    // ← Added for Coupon
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Place a new order with Coupon Support
     */
    public function store(Request $request)
    {
        \Log::info("===== ORDER CREATION STARTED =====", [
            'user_id'        => $request->user()->id,
            'payment_method' => $request->payment_method
        ]);

        $user = $request->user();

        if ($user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can place orders.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'vendor_id'        => 'required|exists:users,id',
            'user_bus_stop_id' => 'required|exists:popular_bus_stops,id',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'address'          => 'required|string',
            'phone'            => 'required|string',
            'notes'            => 'nullable|string',
            'payment_method'   => 'required|in:paystack,bank_transfer,wallet',
            'payment_proof'    => 'required_if:payment_method,bank_transfer|string|nullable',
            'coupon_code'      => 'nullable|string|max:50',           // ← Added
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Service Charge
        $serviceCharge = Setting::where('key', 'service_charge_amount')->first()?->value ?? 200;

        // Calculate Items Total
        $itemsTotal = 0;
        $orderItemsData = [];

        $itemIds = collect($request->items)->pluck('item_id')->unique();
        $items = Item::whereIn('id', $itemIds)
                     ->where('vendor_id', $request->vendor_id)
                     ->get()
                     ->keyBy('id');

        foreach ($request->items as $itemData) {
            $item = $items->get($itemData['item_id']);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more items invalid or not from this vendor.',
                ], 400);
            }

            $subtotal = $item->price * $itemData['quantity'];
            $itemsTotal += $subtotal;

            $orderItemsData[] = [
                'item_id'  => $item->id,
                'quantity' => $itemData['quantity'],
                'price'    => $item->price,
                'subtotal' => $subtotal,
            ];
        }

        // Get Vendor & Delivery Fee
        $vendor = User::where('id', $request->vendor_id)
                      ->where('role', 'vendor')
                      ->with('vendorProfile.popularBusStop')
                      ->first();

        if (!$vendor || !$vendor->vendorProfile || !$vendor->vendorProfile->popular_bus_stop_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor does not have a Popular Bus Stop assigned.',
            ], 400);
        }

        $interval = DeliveryInterval::where(function ($query) use ($vendor, $request) {
                $query->where('from_stop_id', $vendor->vendorProfile->popular_bus_stop_id)
                      ->where('to_stop_id', $request->user_bus_stop_id);
            })
            ->orWhere(function ($query) use ($vendor, $request) {
                $query->where('from_stop_id', $request->user_bus_stop_id)
                      ->where('to_stop_id', $vendor->vendorProfile->popular_bus_stop_id);
            })
            ->first();

        if (!$interval) {
            return response()->json([
                'success' => false,
                'message' => 'No delivery route found between selected bus stops.',
            ], 400);
        }

        $deliveryFee = (int) $interval->price;
        $grandTotal  = $itemsTotal + $deliveryFee + $serviceCharge;

        // ====================== COUPON LOGIC ======================
        $couponDiscount = 0;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))
                            ->where('is_active', true)
                            ->first();

            if ($coupon && $coupon->isValid()) {
                $vendorCategory = $vendor->vendorProfile->type ?? 'kitchen'; // 'kitchen', 'supermarket', 'pharmacy'

                // Check if coupon applies to this vendor category or dispatch
                if (in_array($vendorCategory, $coupon->applicable_categories ?? []) || 
                    in_array('dispatch', $coupon->applicable_categories ?? [])) {
                    
                    $couponDiscount = min($coupon->discount_amount, $grandTotal);
                    $coupon->increment('used_count');
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'This coupon is not valid for the selected category.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon is invalid, expired, or maximum uses reached.',
                ], 400);
            }
        }

        $grandTotal = max(0, $grandTotal - $couponDiscount);

        // ====================== CREATE ORDER ======================
        $order = DB::transaction(function () use ($user, $request, $vendor, $itemsTotal, $deliveryFee, $serviceCharge, $grandTotal, $orderItemsData, $coupon, $couponDiscount) {

            $paymentStatus = 'pending';
            $orderStatus   = 'ordered';

            // WALLET PAYMENT - Immediate success
            if ($request->payment_method === 'wallet') {
                if (!$user->hasSufficientWalletBalance($grandTotal)) {
                    throw new \Exception('Insufficient wallet balance.');
                }

                $user->getCustomerWallet()->decrement('balance', $grandTotal);

                $paymentStatus = 'paid';
                $orderStatus   = 'paid';
            }

            // Create Order
            $order = Order::create([
                'customer_id'        => $user->id,
                'vendor_id'          => $request->vendor_id,
                'user_bus_stop_id'   => $request->user_bus_stop_id,
                'vendor_bus_stop_id' => $vendor->vendorProfile->popular_bus_stop_id,
                'status'             => $orderStatus,
                'payment_method'     => $request->payment_method,
                'payment_status'     => $paymentStatus,
                'items_total'        => $itemsTotal,
                'delivery_fee'       => $deliveryFee,
                'service_charge'     => $serviceCharge,
                'grand_total'        => $grandTotal,
                'coupon_id'          => $coupon?->id,
                'coupon_discount'    => $couponDiscount,
                'customer_address'   => $request->address,
                'vendor_address'     => $vendor->address ?? 'N/A',
                'phone'              => $request->phone,
                'notes'              => $request->notes ?? null,
            ]);

            // Insert Order Items
            foreach ($orderItemsData as &$item) {
                $item['order_id'] = $order->id;
            }
            OrderItem::insert($orderItemsData);

            // Record Wallet Transaction
            if ($request->payment_method === 'wallet') {
                $user->transactions()->create([
                    'amount'            => $grandTotal,
                    'type'              => 'debit',
                    'description'       => 'Payment for Order #' . $order->order_number . ' (Wallet)',
                    'transactable_type' => Order::class,
                    'transactable_id'   => $order->id,
                ]);
            }

            return $order;
        });

        // Bank Transfer Proof
        if ($request->payment_method === 'bank_transfer' && $request->filled('payment_proof')) {
            $proofPath = $this->saveProofImage($request->payment_proof, $order->id);
            if ($proofPath) {
                $order->update(['payment_proof' => $proofPath]);
            }
        }

        // Paystack (Future)
        if ($request->payment_method === 'paystack') {
            return response()->json([
                'success'       => true,
                'message'       => 'Order created – proceed to Paystack payment',
                'order'         => $order->load('items.item'),
                'delivery_fee'  => $deliveryFee,
                'delivery_code' => $order->delivery_code ?? null,
            ], 201);
        }

        // Success Message
        $message = $request->payment_method === 'wallet'
            ? 'Order placed successfully using wallet balance. Vendor has been notified.'
            : 'Order placed successfully. Awaiting admin payment confirmation.';

        return response()->json([
            'success'         => true,
            'message'         => $message,
            'order'           => $order->load('items.item'),
            'delivery_fee'    => $deliveryFee,
            'coupon_discount' => $couponDiscount,
            'delivery_code'   => $order->delivery_code ?? null,
        ], 201);
    }

    /**
     * Save payment proof image for bank transfer
     */
    protected function saveProofImage($base64, $orderId)
    {
        try {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
            $image = base64_decode($base64);

            if ($image === false) {
                throw new \Exception('Invalid base64 image data');
            }

            $path = 'orders/proof/' . $orderId . '_' . Str::random(10) . '.jpg';
            Storage::disk('public')->put($path, $image);

            return $path;
        } catch (\Exception $e) {
            Log::error('Payment proof save failed for order #' . $orderId . ': ' . $e->getMessage());
            return null;
        }
    }

    // ==================== UNCHANGED METHODS ====================

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->customer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'This order does not belong to you.',
            ], 403);
        }

        $order->load(['items.item', 'vendor.vendorProfile', 'dispatcher']);

        return response()->json([
            'success' => true,
            'order' => [
                'id'              => $order->id,
                'order_number'    => $order->order_number,
                'status'          => $order->status,
                'grand_total'     => $order->grand_total,
                'delivery_fee'    => $order->delivery_fee,
                'delivery_code'   => $order->delivery_code,
                'customer_address'=> $order->customer_address,
                'vendor'          => $order->vendor ? [
                    'name'    => $order->vendor->vendorProfile->company_name ?? $order->vendor->name,
                    'address' => $order->vendor_address,
                ] : null,
                'dispatcher'      => $order->dispatcher ? [
                    'name'  => $order->dispatcher->name ?? 'N/A',
                    'phone' => $order->dispatcher->phone ?? 'N/A',
                ] : null,
                'items'           => $order->items->map(fn($item) => [
                    'item_name' => $item->item->name,
                    'quantity'  => $item->quantity,
                    'price'     => $item->price,
                    'subtotal'  => $item->subtotal,
                ]),
                'progress_steps'  => $order->progress_steps ?? [
                    'ordered'   => 'Order Placed',
                    'paid'      => 'Payment Confirmed',
                    'accepted'  => 'Vendor Received Order',
                    'packaged'  => 'Packaged & Ready',
                    'picked_up' => 'Picked Up by Dispatcher',
                    'enroute'   => 'Enroute to You',
                    'delivered' => 'Delivered',
                ],
                'current_step'    => $order->status,
            ]
        ]);
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can view order history.',
            ], 403);
        }

        $orders = Order::where('customer_id', $user->id)
            ->with(['vendor.vendorProfile', 'items.item', 'dispatcher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'id'            => $order->id,
                    'order_number'  => $order->order_number,
                    'status'        => $order->status,
                    'grand_total'   => $order->grand_total,
                    'delivery_fee'  => $order->delivery_fee,
                    'delivery_code' => $order->delivery_code,
                    'created_at'    => $order->created_at->toDateTimeString(),
                    'vendor'        => $order->vendor ? [
                        'name' => $order->vendor->vendorProfile->company_name ?? $order->vendor->name,
                    ] : null,
                    'dispatcher'    => $order->dispatcher ? [
                        'name'  => $order->dispatcher->name ?? 'N/A',
                        'phone' => $order->dispatcher->phone ?? 'N/A',
                    ] : null,
                    'items_count'   => $order->items->count(),
                    'progress_steps'=> $order->progress_steps ?? [
                        'ordered'   => 'Order Placed',
                        'paid'      => 'Payment Confirmed',
                        'accepted'  => 'Vendor Received Order',
                        'packaged'  => 'Packaged & Ready',
                        'picked_up' => 'Picked Up by Dispatcher',
                        'enroute'   => 'Enroute to You',
                        'delivered' => 'Delivered',
                    ],
                    'current_step'  => $order->status,
                ];
            }),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }
}