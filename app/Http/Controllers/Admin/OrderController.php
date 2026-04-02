<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'vendor.vendorProfile', 'dispatcher'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'customer',
            'vendor.vendorProfile',
            'dispatcher.dispatcherProfile',
            'items.item'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                'pending_payment',
                'paid',
                'accepted',
                'packaged',
                'picked_up',
                'enroute',
                'delivered',
                'cancelled'
            ])],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'status' => $validated['status'],
                'actual_delivery_time' => $validated['status'] === 'delivered' ? now() : null,
            ]);
        });

        \Log::info("Admin updated order #{$order->id} status to {$validated['status']}", [
            'admin_id' => auth()->id(),
            'order_id' => $order->id,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])));
    }

    /**
     * Approve or reject bank transfer payment (admin only)
     */
    public function verifyPayment(Request $request, Order $order)
    {
        if ($order->payment_method !== 'bank_transfer') {
            return redirect()->back()->with('error', 'This order is not using bank transfer.');
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Payment already processed.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $message = '';

        DB::transaction(function () use ($order, $request, &$message) {

            if ($request->action === 'approve') {

                // ✅ UPDATE ORDER
                $order->update([
                    'payment_status' => 'success',
                    'status'         => 'paid',
                ]);

                // ✅ CREATE CUSTOMER TRANSACTION (FIXED)
                \App\Models\Transaction::create([
                    'user_id' => $order->customer_id,
                    'amount' => $order->grand_total,
                    'type' => 'debit',
                    'description' => 'Payment for Order #' . $order->order_number,
                    'transactable_type' => \App\Models\Order::class,
                    'transactable_id' => $order->id,
                ]);

                $message = '✅ Payment approved successfully. Order is now paid.';

            } else {

                $order->update([
                    'payment_status' => 'failed',
                    'status'         => 'cancelled',
                ]);

                $message = '❌ Payment rejected. Order has been cancelled.';
            }
        });

        \Log::info("Admin verified payment for order #{$order->id}", [
            'admin_id' => auth()->id(),
            'action' => $request->action,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', $message);
    }
}