<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorOrderController extends Controller
{
    /**
     * Get all orders for the authenticated vendor (with full items)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can access this.',
            ], 403);
        }

        $orders = Order::where('vendor_id', $user->id)
            ->with(['customer', 'items.item', 'dispatcher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $mappedOrders = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'vendor_earnings' => $order->items_total,
                'items_total' => $order->items_total,
                'delivery_fee' => $order->delivery_fee,
                'service_charge' => $order->service_charge,
                'grand_total' => $order->grand_total,
                'customer' => $order->customer ? [
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'item_name' => $item->item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'items_count' => $order->items->count(),
                'created_at' => $order->created_at->toDateTimeString(),
                'delivery_address' => $order->customer_address,
                'progress_steps' => [
                    'ordered'   => 'Order Placed',
                    'paid'      => 'Payment Confirmed',
                    'accepted'  => 'Order Accepted',           // ← updated
                    'packaged'  => 'Packaged & Ready',
                    'picked_up' => 'Picked Up',
                    'enroute'   => 'Enroute',
                    'delivered' => 'Delivered',
                ],
                'current_step' => $order->status,
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $mappedOrders,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Get single order full details (with items)
     */
    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can access this.',
            ], 403);
        }

        if ($order->vendor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'This order does not belong to you.',
            ], 403);
        }

        $order->load(['customer', 'items.item', 'dispatcher']);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'vendor_earnings' => $order->items_total,
                'items_total' => $order->items_total,
                'delivery_fee' => $order->delivery_fee,
                'service_charge' => $order->service_charge,
                'grand_total' => $order->grand_total,
                'customer' => $order->customer ? [
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'item_name' => $item->item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'created_at' => $order->created_at->toDateTimeString(),
                'delivery_address' => $order->customer_address,
                'progress_steps' => [
                    'ordered'   => 'Order Placed',
                    'paid'      => 'Payment Confirmed',
                    'accepted'  => 'Order Accepted',           // ← updated
                    'packaged'  => 'Packaged & Ready',
                    'picked_up' => 'Picked Up',
                    'enroute'   => 'Enroute',
                    'delivered' => 'Delivered',
                ],
                'current_step' => $order->status,
            ]
        ]);
    }

    /**
     * Update order status (vendor only)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can update status.',
            ], 403);
        }

        if ($order->vendor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'This order does not belong to you.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,packaged',  // ← changed from received to accepted
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $allowedTransitions = [
            'ordered'  => ['accepted'],
            'paid'     => ['accepted'],
            'accepted' => ['packaged'],
            'packaged' => [],
        ];

        $currentStatus = $order->status;

        if (!isset($allowedTransitions[$currentStatus]) ||
            !in_array($request->status, $allowedTransitions[$currentStatus])) {
            return response()->json([
                'success' => false,
                'message' => "Cannot change status from '$currentStatus' to '{$request->status}'",
            ], 400);
        }

        $order->update([
            'status' => $request->status,
        ]);

        // Optional: notify dispatcher when packaged
        if ($request->status === 'packaged' && $order->dispatcher_id) {
            // TODO: send push notification or email to dispatcher
        }

        $order->load(['customer', 'items.item', 'dispatcher']);

        return response()->json([
            'success' => true,
            'message' => $request->status === 'accepted'
                ? 'Order successfully accepted!'
                : 'Order marked as Packaged & Ready!',
            'order' => $order,
        ]);
    }
}