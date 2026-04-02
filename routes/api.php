<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VendorOrderController;
use App\Http\Controllers\Api\DispatchController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\Api\MenuRequestController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NavController;
use App\Http\Controllers\Api\VendorDashboardController;
use App\Http\Controllers\Api\DispatchDashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DeliveryIntervalController;
use App\Http\Controllers\Api\CustomerWalletController;   
use App\Http\Controllers\Api\CouponController;           // ← NEW

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public routes → no auth (strict rate limit: 10/min)
| Protected routes → Sanctum + moderate rate limit (60/min)
|
*/

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login',    [AuthController::class, 'login'])   ->name('api.login');

    // Public: Fetch all Popular Bus Stops
    Route::get('/popular-bus-stops', function () {
        $stops = \App\Models\PopularBusStop::orderBy('name')->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'data'    => $stops
        ]);
    })->name('api.popular-bus-stops');
});

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

    // ─── Authentication & User Profile ───────────────────────────────
    Route::post('/logout',  [AuthController::class, 'logout']) ->name('api.logout');

    Route::get('/user', fn(Request $r) => $r->user())->name('api.user');

    Route::get('/profile', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'username'   => $user->username,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'avatar'     => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'address'    => $user->address,
                'state'      => $user->state,
                'is_active'  => $user->is_active,
                'created_at' => $user->created_at->toDateTimeString(),
            ]
        ]);
    })->name('api.profile');

    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.profile.update');

    // ─── Delivery Fee & Bus Stop Logic ─────────────────────────
    Route::get('/bus-stops', [DeliveryIntervalController::class, 'index'])
         ->name('api.bus-stops');

    Route::get('/delivery-fee', [DeliveryIntervalController::class, 'getDeliveryFee'])
         ->name('api.delivery.fee');

    // ─── CUSTOMER WALLET ROUTES (NEW) ───────────────────────────────
    Route::prefix('customer/wallet')->group(function () {
        Route::get('/', [CustomerWalletController::class, 'index'])
             ->name('api.customer.wallet.index');           // Get wallet balance

        Route::post('/fund', [CustomerWalletController::class, 'fund'])
             ->name('api.customer.wallet.fund');            // Request to fund wallet

        Route::get('/transactions', [CustomerWalletController::class, 'transactions'])
             ->name('api.customer.wallet.transactions');    // Wallet history
    });

        // ====================== COUPON ROUTES ======================
    Route::prefix('coupons')->group(function () {
        Route::get('/active-count', [CouponController::class, 'activeCount']);     // For badge on home
        Route::post('/redeem',      [CouponController::class, 'redeem']);         // Customer redeems code
        Route::get('/my-coupons',   [CouponController::class, 'myCoupons']);      // Optional

        // ← NEW: This is what the CustomerCoupon page needs
        Route::get('/active',       [CouponController::class, 'active']);         
    });
    // ─── Customer Orders ─────────────────────────────────────────────
    Route::post('/orders',           [OrderController::class, 'store'])   ->name('api.orders.store');
    Route::get('/orders/{order}',    [OrderController::class, 'show'])    ->name('api.orders.show');
    Route::get('/my-orders',         [OrderController::class, 'myOrders'])->name('api.orders.my-orders');

    // ─── Vendor Routes ───────────────────────────────────────────────
    Route::prefix('vendor')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('api.vendor.dashboard');

        Route::get('/orders',        [VendorOrderController::class, 'index'])->name('api.vendor.orders.index');
        Route::get('/orders/{order}',[VendorOrderController::class, 'show'])->name('api.vendor.orders.show');
    // vigil:ignore debug_routes
        Route::patch('/orders/{order}/status', [VendorOrderController::class, 'updateStatus'])->name('api.vendor.orders.update-status');

        Route::get('/profile', function (Request $request) {
            $user = $request->user();
            if ($user->role !== 'vendor') {
                return response()->json(['success' => false, 'message' => 'Only vendors'], 403);
            }
            $vendorProfile = $user->vendorProfile;
            if (!$vendorProfile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor profile not found. Contact admin.'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'phone'          => $user->phone,
                    'address'        => $user->address,
                    'avatar'         => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'company_name'   => $vendorProfile->company_name,
                    'type'           => $vendorProfile->type,
                    'logo'           => $vendorProfile->logo ? asset('storage/' . $vendorProfile->logo) : null,
                    'bank_name'      => $vendorProfile->bank_name,
                    'account_number' => $vendorProfile->account_number,
                    'account_name'   => $vendorProfile->account_name,
                    'is_verified'    => $vendorProfile->is_verified,
                    'popular_bus_stop' => $vendorProfile->popularBusStop ? [
                        'id'   => $vendorProfile->popularBusStop->id,
                        'name' => $vendorProfile->popularBusStop->name,
                    ] : null,
                    'created_at'     => $user->created_at->toDateTimeString(),
                ]
            ]);
        })->name('api.vendor.profile');
    });

    // ─── Dispatcher Routes ───────────────────────────────────────────
    Route::prefix('dispatch')->group(function () {
        Route::get('/dashboard', [DispatchDashboardController::class, 'index'])->name('api.dispatch.dashboard');
        Route::get('/pickups', [DispatchController::class, 'pickups'])->name('api.dispatch.pickups');
        Route::patch('/pickups/{order}/accept', [DispatchController::class, 'acceptPickup'])->name('api.dispatch.accept-pickup');
        Route::patch('/orders/{order}/enroute', [DispatchController::class, 'markEnroute'])->name('api.dispatch.mark-enroute');

        Route::get('/orders/{order}', [DispatchController::class, 'showOrder'])->name('api.dispatch.orders.show');

        Route::post('/orders/{order}/verify-code', [DispatchController::class, 'verifyCode'])
             ->name('api.dispatch.orders.verify-code');

        Route::match(['get', 'options'], '/trips', [DispatchController::class, 'myTrips'])
             ->name('api.dispatch.trips');

        Route::get('/profile', function (Request $request) {
            $user = $request->user();

            if ($user->role !== 'dispatcher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only dispatchers can access this profile'
                ], 403);
            }

            $dispatcherProfile = $user->dispatcherProfile;

            return response()->json([
                'success' => true,
                'data' => [
                    'id'              => $user->id,
                    'full_name'       => $dispatcherProfile?->full_name ?? $user->name,
                    'phone'           => $user->phone,
                    'email'           => $user->email,
                    'address'         => $dispatcherProfile?->address ?? $user->address,
                    'avatar'          => $dispatcherProfile?->avatar 
                                        ? asset('storage/' . $dispatcherProfile->avatar) 
                                        : ($user->avatar ? asset('storage/' . $user->avatar) : null),
                    'plate_number'    => $dispatcherProfile?->plate_number,
                    'nin_number'      => $dispatcherProfile?->nin_number,
                    'grantor_name'    => $dispatcherProfile?->grantor_name,
                    'grantor_address' => $dispatcherProfile?->grantor_address,
                    'bank_name'       => $dispatcherProfile?->bank_name,
                    'account_number'  => $dispatcherProfile?->account_number,
                    'account_name'    => $dispatcherProfile?->account_name,
                    'is_active'       => $dispatcherProfile?->is_active ?? true,
                    'created_at'      => $user->created_at->toDateTimeString(),
                ]
            ]);
        })->name('api.dispatch.profile');
    });

    // ─── Withdrawals, Menu Requests, etc. (unchanged) ───────────────────────────
    Route::post('/withdrawals',       [WithdrawalController::class, 'request'])   ->name('api.withdrawals.request');
    Route::get('/withdrawals',        [WithdrawalController::class, 'myRequests'])->name('api.withdrawals.my-requests');

    Route::patch('/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('api.withdrawals.approve');
    Route::patch('/withdrawals/{withdrawal}/reject',  [WithdrawalController::class, 'reject'])->name('api.withdrawals.reject'); 

    Route::post('/menu-requests', [MenuRequestController::class, 'store'])->name('api.menu-requests.store');
    Route::get('/menu-requests',  [MenuRequestController::class, 'myRequests'])->name('api.menu-requests.my');

    // ─── General / Public ────────────────────────────────────────────
    Route::get('/home/items',   [ItemController::class, 'homeItems'])->name('api.home.items');
    Route::get('/nav-data',     [NavController::class, 'index'])->name('api.nav.data');
    Route::get('/settings',     [ItemController::class, 'settings']);

    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('api.payment.callback');

    // Public vendors list
    Route::get('/vendors', function (Request $request) {
        $type = $request->query('type');
        $query = User::where('role', 'vendor')->with('vendorProfile');
        if ($type) $query->whereHas('vendorProfile', fn($q) => $q->where('type', $type));
        
        $vendors = $query->get()->map(fn($u) => [
            'id'          => $u->id,
            'name'        => $u->vendorProfile->company_name ?? $u->name,
            'logo'        => $u->vendorProfile->logo ? asset('storage/' . $u->vendorProfile->logo) : null,
            'avatar'      => $u->avatar ? asset('storage/' . $u->avatar) : null,
            'type'        => $u->vendorProfile->type ?? null,
            'address'     => $u->address,
            'state'       => $u->state,
        ]);
        return response()->json(['success' => true, 'vendors' => $vendors]);
    })->name('api.vendors.index');
});

// ─── CORS Preflight Catch-All ──────
Route::options('{any}', fn() => response('', 204)
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type,Authorization')
)->where('any', '.*');