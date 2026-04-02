<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ====================== BASIC STATS ======================
        $totalCustomers   = User::where('role', 'customer')->count();
        $totalVendors     = User::where('role', 'vendor')->count();
        $totalDispatchers = User::where('role', 'dispatcher')->count();

        // Deliveries
        $todayDeliveries = Order::where('status', 'delivered')
            ->whereDate('actual_delivery_time', Carbon::today())
            ->count();

        $totalDeliveries = Order::where('status', 'delivered')->count();

        // ====================== ADMIN USER & WALLETS ======================
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            // Admin Delivery Wallet (40%)
            $deliveryWallet = Wallet::where('user_id', $admin->id)
                                    ->where('wallet_type', 'delivery')
                                    ->first();

            if (!$deliveryWallet) {
                $deliveryWallet = Wallet::create([
                    'user_id'      => $admin->id,
                    'wallet_type'  => 'delivery',
                    'balance'      => 0.00,
                    'total_earned' => 0.00,
                ]);
            }

            // Admin Service Wallet (100%)
            $serviceWallet = Wallet::where('user_id', $admin->id)
                                   ->where('wallet_type', 'service')
                                   ->first();

            if (!$serviceWallet) {
                $serviceWallet = Wallet::create([
                    'user_id'      => $admin->id,
                    'wallet_type'  => 'service',
                    'balance'      => 0.00,
                    'total_earned' => 0.00,
                ]);
            }
        } else {
            $deliveryWallet = null;
            $serviceWallet = null;
            Log::warning('No admin user found with role "admin"');
        }

        // ====================== EARNINGS CALCULATION ======================
        $todayDeliveryFees   = Order::where('status', 'delivered')
            ->whereDate('actual_delivery_time', Carbon::today())
            ->sum('delivery_fee');

        $todayServiceCharges = Order::where('status', 'delivered')
            ->whereDate('actual_delivery_time', Carbon::today())
            ->sum('service_charge');

        // Today's Earnings
        $todayEarnings = round(($todayDeliveryFees * 0.40) + $todayServiceCharges, 2);

        // Total (All-time) Earnings
        $totalDeliveryFees   = Order::where('status', 'delivered')->sum('delivery_fee');
        $totalServiceCharges = Order::where('status', 'delivered')->sum('service_charge');
        $totalEarnings       = round(($totalDeliveryFees * 0.40) + $totalServiceCharges, 2);

        // Today's shares
        $todayAdminDeliveryShare = round($todayDeliveryFees * 0.40, 2);
        $todayAdminServiceShare  = $todayServiceCharges;

        // NEW: Total cumulative shares (this is what you wanted)
        $totalAdminDeliveryShare = round($totalDeliveryFees * 0.40, 2);
        $totalAdminServiceShare  = $totalServiceCharges;

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
            ->where('type', 'credit')
            ->where(function ($query) {
                $query->where('description', 'like', '%delivery fee share%')
                      ->orWhere('description', 'like', '%service charge%')
                      ->orWhere('description', 'like', '%Payment for Order%');
            })
            ->latest()
            ->take(20)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalVendors',
            'totalDispatchers',
            'todayDeliveries',
            'totalDeliveries',
            'todayEarnings',
            'totalEarnings',
            'recentTransactions',
            'deliveryWallet',
            'serviceWallet',
            'todayAdminDeliveryShare',
            'todayAdminServiceShare',
            'totalAdminDeliveryShare',     // ← Added
            'totalAdminServiceShare'       // ← Added
        ));
    }
}