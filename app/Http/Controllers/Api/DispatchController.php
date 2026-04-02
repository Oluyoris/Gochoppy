<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\AdminDeliverySetting;
use App\Models\User;                    // ← Added this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Log;

class DispatchController extends Controller
{
    /**
     * Get ready pickup orders (dispatch only)
     */
    public function pickups(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json([
                'success' => false,
                'message' => 'Only dispatchers can access pickups.',
            ], 403);
        }

        $orders = Order::whereIn('status', ['packaged', 'ready'])
                       ->whereNull('dispatcher_id')
                       ->with(['vendor.vendorProfile', 'customer', 'items.item', 'userBusStop'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        return response()->json([
            'success' => true,
            'orders' => $orders->getCollection()->map(function ($order) {
                return [
                    'id'            => $order->id,
                    'order_number'  => $order->order_number,
                    'status'        => $order->status,
                    'grand_total'   => $order->grand_total,
                    'customer'      => $order->customer ? [
                        'name'     => $order->customer->name,
                        'phone'    => $order->customer->phone,
                        'address'  => $order->customer_address,
                        'bus_stop' => $order->userBusStop?->name,
                    ] : null,
                    'vendor'        => $order->vendor ? [
                        'name'    => $order->vendor->vendorProfile->company_name ?? $order->vendor->name,
                        'address' => $order->vendor_address,
                        'phone'   => $order->vendor->phone ?? 'N/A',
                    ] : null,
                    'items_count'   => $order->items->count(),
                    'items'         => $order->items->map(function ($item) {
                        return [
                            'item_name' => $item->item->name ?? 'Unknown Item',
                            'quantity'  => $item->quantity,
                            'price'     => $item->price,
                            'subtotal'  => $item->subtotal ?? ($item->price * $item->quantity),
                        ];
                    })->toArray(),
                    'created_at'    => $order->created_at->toDateTimeString(),
                ];
            })->values()->toArray(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /**
     * Show order details (dispatcher only)
     */
    public function showOrder(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json([
                'success' => false,
                'message' => 'Only dispatchers can view order details here.',
            ], 403);
        }

        if ($order->dispatcher_id !== $user->id && $order->dispatcher_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not assigned to you.',
            ], 403);
        }

        $order->load(['vendor.vendorProfile', 'customer', 'items.item', 'userBusStop']);

        return response()->json([
            'success' => true,
            'order' => [
                'id'                => $order->id,
                'order_number'      => $order->order_number,
                'status'            => $order->status,
                'grand_total'       => $order->grand_total,
                'delivery_fee'      => $order->delivery_fee,
                'service_charge'    => $order->service_charge,
                'customer_address'  => $order->customer_address,
                'phone'             => $order->phone,
                'notes'             => $order->notes,

                'user_bus_stop' => $order->userBusStop ? [
                    'id'   => $order->userBusStop->id,
                    'name' => $order->userBusStop->name,
                ] : null,

                'vendor' => $order->vendor ? [
                    'name'    => $order->vendor->vendorProfile->company_name ?? $order->vendor->name,
                    'address' => $order->vendor_address,
                    'phone'   => $order->vendor->phone ?? 'N/A',
                ] : null,

                'customer' => $order->customer ? [
                    'name'  => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'address' => $order->customer_address,
                ] : null,

                'items' => $order->items->map(function ($item) {
                    return [
                        'item_name' => $item->item->name ?? 'Unknown Item',
                        'quantity'  => $item->quantity,
                        'price'     => $item->price,
                        'subtotal'  => $item->subtotal ?? ($item->price * $item->quantity),
                    ];
                })->toArray(),

                'created_at' => $order->created_at->toDateTimeString(),
            ]
        ]);
    }

    /**
     * Accept pickup order (dispatch only)
     */
    public function acceptPickup(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json(['success' => false, 'message' => 'Only dispatchers can accept pickups.'], 403);
        }

        if (!in_array($order->status, ['packaged', 'ready']) || $order->dispatcher_id) {
            return response()->json(['success' => false, 'message' => 'Order not available for pickup.'], 400);
        }

        $validator = Validator::make($request->all(), ['notes' => 'nullable|string|max:500']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $order->update([
            'dispatcher_id' => $user->id,
            'status' => 'picked_up',
            'notes' => $request->notes ?? $order->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pickup accepted – order now picked up',
            'order' => $order->fresh()->load(['vendor.vendorProfile', 'customer', 'items.item', 'userBusStop']),
        ]);
    }

    /**
     * Mark order as enroute (dispatch only)
     */
    public function markEnroute(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json(['success' => false, 'message' => 'Only dispatchers can update status.'], 403);
        }

        if ($order->dispatcher_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'This order is not assigned to you.'], 403);
        }

        if ($order->status !== 'picked_up') {
            return response()->json(['success' => false, 'message' => 'Order must be picked up before marking as enroute.'], 400);
        }

        $validator = Validator::make($request->all(), ['notes' => 'nullable|string|max:500']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $order->update([
            'status' => 'enroute',
            'notes' => $request->notes ?? $order->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as enroute',
            'order' => $order->fresh(),
        ]);
    }

    /**
     * Verify delivery code + Credit Wallets with DYNAMIC split from Admin Settings
     * FIXED - Now uses real admin user instead of hard-coded ID 1
     */
    public function verifyCode(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json(['success' => false, 'message' => 'Only dispatchers can verify delivery code.'], 403);
        }

        if ($order->dispatcher_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'You are not assigned to this order.'], 403);
        }

        if ($order->status === 'delivered') {
            return response()->json(['success' => false, 'message' => 'Order already delivered.'], 400);
        }

        $validator = Validator::make($request->all(), ['code' => 'required|string|size:6']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if ($order->delivery_code !== $request->code) {
            return response()->json(['success' => false, 'message' => 'Invalid delivery code.'], 400);
        }

        // Mark as delivered
        $order->update([
            'status' => 'delivered',
            'actual_delivery_time' => now(),
        ]);

        $deliveryFee   = (float) $order->delivery_fee;
        $serviceCharge = (float) $order->service_charge;
        $itemsTotal    = (float) $order->items_total;

        // Get dynamic split from AdminDeliverySetting
        $setting = AdminDeliverySetting::where('is_active', true)->first();

        if (!$setting) {
            $setting = AdminDeliverySetting::create([
                'dispatch_percentage' => 60,
                'admin_percentage'    => 40,
                'is_active'           => true,
            ]);
        }

        $dispatcherShare    = $deliveryFee * ($setting->dispatch_percentage / 100);
        $adminDeliveryShare = $deliveryFee * ($setting->admin_percentage / 100);

        // Find real admin user dynamically
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            Log::error('No admin user found with role "admin" for wallet crediting.');
        }

        // 1. Credit Vendor - 100% of items_total
        $vendor = $order->vendor;
        $vendorWallet = Wallet::firstOrCreate(
            ['user_id' => $vendor->id, 'wallet_type' => 'main'],
            ['balance' => 0, 'total_earned' => 0]
        );

        $vendorWallet->increment('balance', $itemsTotal);
        $vendorWallet->increment('total_earned', $itemsTotal);

        $vendor->transactions()->create([
            'amount'            => $itemsTotal,
            'type'              => 'credit',
            'description'       => 'Payment for Order #' . $order->order_number . ' (Items)',
            'transactable_type' => Order::class,
            'transactable_id'   => $order->id,
        ]);

        // 2. Credit Dispatcher - Dynamic % of delivery_fee
        $dispatcherWallet = Wallet::firstOrCreate(
            ['user_id' => $user->id, 'wallet_type' => 'main'],
            ['balance' => 0, 'total_earned' => 0]
        );

        $dispatcherWallet->increment('balance', $dispatcherShare);
        $dispatcherWallet->increment('total_earned', $dispatcherShare);

        $user->transactions()->create([
            'amount'            => $dispatcherShare,
            'type'              => 'credit',
            'description'       => 'Delivery fee share ('.$setting->dispatch_percentage.'%) for Order #' . $order->order_number,
            'transactable_type' => Order::class,
            'transactable_id'   => $order->id,
        ]);

        // 3. Credit Admin Delivery Wallet (only if admin exists)
        if ($admin) {
            $adminDeliveryWallet = Wallet::firstOrCreate(
                ['user_id' => $admin->id, 'wallet_type' => 'delivery'],
                ['balance' => 0, 'total_earned' => 0]
                
            );

            $adminDeliveryWallet->increment('balance', $adminDeliveryShare);
            $adminDeliveryWallet->increment('total_earned', $adminDeliveryShare);
            $adminDeliveryWallet->refresh();
        }

        // 4. Credit Admin Service Wallet (only if admin exists)
        if ($admin) {
            $adminServiceWallet = Wallet::firstOrCreate(
                ['user_id' => $admin->id, 'wallet_type' => 'service'],
                ['balance' => 0, 'total_earned' => 0]
            );

            $adminServiceWallet->increment('balance', $serviceCharge);
            $adminServiceWallet->increment('total_earned', $serviceCharge);
            $adminServiceWallet->refresh();
        }

        // Notifications
        $customer = $order->customer;
        if ($customer && $customer->email) {
            try {
                Mail::to($customer->email)->queue(new OrderNotification(
                    'Order Delivered',
                    'Your order #' . $order->order_number . ' has been delivered. Thank you!',
                    $customer
                ));
            } catch (\Exception $e) {
                Log::error('Customer notification failed: ' . $e->getMessage());
            }
        }

        if ($vendor && $vendor->email) {
            try {
                Mail::to($vendor->email)->queue(new OrderNotification(
                    'Payment Received',
                    'Payment of ₦' . number_format($itemsTotal, 2) . ' received for Order #' . $order->order_number,
                    $vendor
                ));
            } catch (\Exception $e) {
                Log::error('Vendor notification failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery verified – wallets credited successfully!',
            'vendor_credited'     => round($itemsTotal, 2),
            'dispatcher_credited' => round($dispatcherShare, 2),
            'admin_delivery'      => round($adminDeliveryShare, 2),
            'admin_service'       => round($serviceCharge, 2),
            'order' => $order->fresh(),
        ]);
    }

    /**
     * Get all trips assigned to this dispatcher
     */
    public function myTrips(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'dispatcher') {
            return response()->json([
                'success' => false,
                'message' => 'Only dispatchers can access trips.',
            ], 403);
        }

        $trips = Order::where('dispatcher_id', $user->id)
                      ->with(['vendor.vendorProfile', 'customer', 'items.item', 'userBusStop'])
                      ->orderBy('updated_at', 'desc')
                      ->get();

        return response()->json([
            'success' => true,
            'trips' => $trips->map(function ($order) {
                return [
                    'id'            => $order->id,
                    'order_number'  => $order->order_number,
                    'status'        => $order->status,
                    'grand_total'   => $order->grand_total,
                    'customer'      => $order->customer ? [
                        'name'     => $order->customer->name,
                        'phone'    => $order->customer->phone,
                        'address'  => $order->customer_address,
                        'bus_stop' => $order->userBusStop?->name,
                    ] : null,
                    'vendor'        => $order->vendor ? [
                        'name'    => $order->vendor->vendorProfile->company_name ?? $order->vendor->name,
                    ] : null,
                    'items_count'   => $order->items->count(),
                    'created_at'    => $order->created_at->toDateTimeString(),
                    'updated_at'    => $order->updated_at->toDateTimeString(),
                ];
            })->values()->toArray(),
        ]);
    }
}