<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use DevRabiul\LaravelPaystack\Facades\Paystack;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $reference = $request->reference;

        try {
            $transaction = Paystack::verifyTransaction($reference);

            if ($transaction['data']['status'] === 'success') {
                $order = Order::where('order_number', $transaction['data']['reference'])->first();

                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // TODO: Notify vendor/customer "Payment Received"

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment verified successfully',
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid transaction',
        ], 400);
    }
}