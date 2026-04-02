<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can access this dashboard.',
            ], 403);
        }

        $today = Carbon::today();

        // Today's sales
        $todaySales = Order::where('vendor_id', $user->id)
                           ->where('status', 'delivered')
                           ->whereDate('created_at', $today)
                           ->sum('items_total');

        // Total earnings (all time)
        $totalEarnings = Order::where('vendor_id', $user->id)
                              ->where('status', 'delivered')
                              ->sum('items_total');

        // New orders (pending vendor action)
        $newOrdersCount = Order::where('vendor_id', $user->id)
                               ->whereIn('status', ['ordered', 'received'])
                               ->count();

        // Wallet balance
        $wallet = $user->wallet;
        $balance = $wallet ? $wallet->balance : 0;

        return response()->json([
            'success' => true,
            'dashboard' => [
                'wallet_balance' => $balance,
                'today_sales' => $todaySales,
                'total_earnings' => $totalEarnings,
                'new_orders_count' => $newOrdersCount,
                'total_orders' => Order::where('vendor_id', $user->id)->count(),
            ]
        ]);
    }
}