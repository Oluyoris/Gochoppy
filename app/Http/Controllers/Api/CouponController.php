<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Get count of active coupons (for badge on customer home)
     */
    public function activeCount(Request $request)
    {
        $count = Coupon::where('is_active', true)
                       ->whereColumn('used_count', '<', 'max_uses')
                       ->where(function($q) {
                           $q->whereNull('expires_at')
                             ->orWhere('expires_at', '>', now());
                       })
                       ->count();

        return response()->json([
            'success' => true,
            'count'   => $count
        ]);
    }

    /**
     * Get all active coupons for CustomerCoupon page
     */
    public function active(Request $request)
    {
        $coupons = Coupon::where('is_active', true)
                         ->whereColumn('used_count', '<', 'max_uses')
                         ->where(function($q) {
                             $q->whereNull('expires_at')
                               ->orWhere('expires_at', '>', now());
                         })
                         ->select('id', 'code', 'title', 'description', 'discount_amount', 
                                  'applicable_categories', 'max_uses', 'used_count', 'expires_at')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'data'    => $coupons
        ]);
    }

    /**
     * Redeem Coupon - ONE USER CAN USE EACH COUPON ONLY ONCE
     */
    public function redeem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:coupons,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 422);
        }

        $user = $request->user();
        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has expired or reached maximum usage.'
            ], 400);
        }

        // Check if THIS user has already used this coupon
        $alreadyUsed = $coupon->users()->where('user_id', $user->id)->exists();

        if ($alreadyUsed) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used this coupon code before.'
            ], 400);
        }

        // Record usage for this specific user
        $coupon->users()->attach($user->id);

        // Increment global usage count
        $coupon->increment('used_count');

        return response()->json([
            'success' => true,
            'message' => 'Coupon redeemed successfully!',
            'coupon'  => [
                'id'                => $coupon->id,
                'code'              => $coupon->code,
                'title'             => $coupon->title,
                'discount_amount'   => $coupon->discount_amount,
                'applicable_categories' => $coupon->applicable_categories,
            ]
        ]);
    }

    /**
     * Get coupons used by this customer (optional)
     */
    public function myCoupons(Request $request)
    {
        $user = $request->user();

        $coupons = \App\Models\Order::where('customer_id', $user->id)
                    ->whereNotNull('coupon_id')
                    ->with('coupon')
                    ->latest()
                    ->get()
                    ->pluck('coupon')
                    ->unique('id');

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }
}