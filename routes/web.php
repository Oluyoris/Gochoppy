<?php

use Illuminate\Support\Facades\Route;

// ====================== ADMIN PANEL ROUTES ======================
Route::prefix('admin')->name('admin.')->group(function () {

    // Public routes (login)
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])
         ->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);

    // Protected routes (only logged-in admins)
    Route::middleware('auth:admin')->group(function () {

        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
             ->name('dashboard');

        // Vendors, Sub Admins, Settings, Dispatchers, etc. (existing routes)

        Route::resource('vendors', App\Http\Controllers\Admin\VendorController::class);
        Route::get('vendors/{vendor}/transactions', [App\Http\Controllers\Admin\VendorController::class, 'transactions'])
             ->name('vendors.transactions'); 

        Route::resource('sub-admins', App\Http\Controllers\Admin\SubAdminController::class);

        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])
             ->name('settings.index');

        Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])
             ->name('settings.update');

        Route::resource('dispatchers', App\Http\Controllers\Admin\DispatcherController::class);
        
        Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])
             ->name('transactions.index');

        Route::get('withdrawals', [App\Http\Controllers\Admin\WithdrawalController::class, 'index'])
             ->name('withdrawals.index');
        Route::patch('withdrawals/{withdrawal}/approve', [App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])
             ->name('withdrawals.approve');
        Route::patch('withdrawals/{withdrawal}/reject',  [App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])
             ->name('withdrawals.reject'); 
             
        Route::get('menu-requests', [App\Http\Controllers\Admin\MenuRequestController::class, 'index'])
             ->name('menu-requests.index');
        Route::patch('menu-requests/{menuRequest}/approve', [App\Http\Controllers\Admin\MenuRequestController::class, 'approve'])
             ->name('menu-requests.approve');
        Route::patch('menu-requests/{menuRequest}/reject', [App\Http\Controllers\Admin\MenuRequestController::class, 'reject'])
             ->name('menu-requests.reject');

        Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])
             ->name('orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])
             ->name('orders.show');
 // vigil:ignore debug_routes
        Route::patch('orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
             ->name('orders.update-status');
        Route::patch('orders/{order}/verify-payment', [App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])
             ->name('orders.verify-payment');
             
             // ====================== COUPON MANAGEMENT (ADMIN) ======================
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

        Route::get('coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])
             ->name('coupons.toggle-status');

        Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])
             ->name('users.index');
        Route::patch('users/{user}/toggle-active', [App\Http\Controllers\Admin\UserController::class, 'toggleActive'])
             ->name('users.toggle-active');
        Route::get('users/{user}/transactions', [App\Http\Controllers\Admin\UserController::class, 'transactions'])
             ->name('users.transactions');
        Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])
             ->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])
            ->name('users.update');
        Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])
             ->name('subscriptions.index');
        // ... other subscription routes ...

        Route::resource('items', App\Http\Controllers\Admin\ItemController::class);

        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])
             ->name('logout');

        // ====================== NEW: CUSTOMER DEPOSITS ROUTES ======================
        Route::prefix('deposits')->name('deposits.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\DepositController::class, 'index'])
                 ->name('index');
            Route::get('/{deposit}', [App\Http\Controllers\Admin\DepositController::class, 'show'])
                 ->name('show');                    // ← NEW: Details page

            Route::post('/{deposit}/approve', [App\Http\Controllers\Admin\DepositController::class, 'approve'])
                 ->name('approve');

            Route::post('/{deposit}/reject', [App\Http\Controllers\Admin\DepositController::class, 'reject'])
                 ->name('reject');
        });
        // ====================== END OF NEW DEPOSITS ROUTES ======================

        // ====================== POPULAR BUS STOPS & DELIVERY INTERVALS ======================
        Route::get('/bus-stops', [App\Http\Controllers\Admin\PopularBusStopController::class, 'index'])
             ->name('bus-stops.index');

        Route::post('/bus-stops', [App\Http\Controllers\Admin\PopularBusStopController::class, 'store'])
             ->name('bus-stops.store');

        Route::get('/bus-stops/{busStop}', [App\Http\Controllers\Admin\PopularBusStopController::class, 'show'])
             ->name('bus-stops.show');

        Route::put('/bus-stops/{busStop}', [App\Http\Controllers\Admin\PopularBusStopController::class, 'update'])
             ->name('bus-stops.update');

        Route::delete('/bus-stops/{busStop}', [App\Http\Controllers\Admin\PopularBusStopController::class, 'destroy'])
             ->name('bus-stops.destroy');

        Route::get('/delivery-settings', [App\Http\Controllers\Admin\AdminDeliverySettingController::class, 'index'])
             ->name('delivery-settings.index');

        Route::put('/delivery-settings', [App\Http\Controllers\Admin\AdminDeliverySettingController::class, 'update'])
             ->name('delivery-settings.update');
    });
});

// Default welcome page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return response()->json(['message' => 'API only – use /api/login'], 403);
})->name('login');