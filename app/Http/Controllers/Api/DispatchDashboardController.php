<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DispatchDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Only dispatchers allowed
        if ($user->role !== 'dispatcher') {
            return response()->json([
                'success' => false,
                'message' => 'Only dispatchers can access this dashboard'
            ], 403);
        }

        try {
            // Get wallet safely using the helper method from User model
            $wallet = $user->getWallet();

            // ─────────────────────────────────────────────────────────────
            // Core Stats
            // ─────────────────────────────────────────────────────────────

            // Total completed deliveries by this dispatcher
            $totalDeliveries = Order::where('dispatcher_id', $user->id)
                ->where('status', 'delivered')
                ->count();

            // Today's completed deliveries
            $todayDeliveries = Order::where('dispatcher_id', $user->id)
                ->where('status', 'delivered')
                ->whereDate('actual_delivery_time', today())
                ->count();

            // Pending pickups (ready/packaged orders not yet assigned)
            $pendingPickups = Order::whereIn('status', ['packaged', 'ready'])
                ->whereNull('dispatcher_id')
                ->count();

            // ─────────────────────────────────────────────────────────────
            // Earnings (from wallet)
            // ─────────────────────────────────────────────────────────────

            $walletBalance   = (float) $wallet->balance;
            $totalEarnings   = (float) $wallet->total_earned;

            // Optional: Today's earnings (if you want to show daily progress)
            $todayEarnings = DB::table('transactions')
                ->where('user_id', $user->id)
                ->where('type', 'credit')
                ->whereDate('created_at', today())
                ->sum('amount');

            // ─────────────────────────────────────────────────────────────
            // Build response (matches what frontend expects)
            // ─────────────────────────────────────────────────────────────

            return response()->json([
                'success' => true,
                'dashboard' => [
                    'wallet_balance'    => $walletBalance,
                    'total_earnings'    => $totalEarnings,
                    'today_earnings'    => (float) $todayEarnings,     // bonus stat
                    'total_deliveries'  => $totalDeliveries,
                    'today_deliveries'  => $todayDeliveries,
                    'pending_pickups'   => $pendingPickups,
                    // You can add more stats here later (e.g., 'current_trips' count)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Dispatcher dashboard failed', [
                'user_id'   => $user->id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data. Please try again.'
            ], 500);
        }
    }
}