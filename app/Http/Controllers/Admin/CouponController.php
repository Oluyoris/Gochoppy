<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of all coupons
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show form to create new coupon
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'                  => 'required|string|unique:coupons,code|max:50',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'discount_amount'       => 'required|numeric|min:100|max:50000',
            'applicable_categories' => 'required|array|min:1',
            'applicable_categories.*' => 'in:kitchen,supermarket,pharmacy,dispatch',
            'max_uses'              => 'required|integer|min:1|max:1000',
            'expires_at'            => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Coupon::create([
            'code'                  => strtoupper(trim($request->code)),
            'title'                 => $request->title,
            'description'           => $request->description,
            'discount_amount'       => $request->discount_amount,
            'applicable_categories' => $request->applicable_categories,
            'max_uses'              => $request->max_uses,
            'expires_at'            => $request->expires_at,
            'is_active'             => true,
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon created successfully!');
    }

    /**
     * Show form to edit coupon
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'code'                  => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'discount_amount'       => 'required|numeric|min:100|max:50000',
            'applicable_categories' => 'required|array|min:1',
            'applicable_categories.*' => 'in:kitchen,supermarket,pharmacy,dispatch',
            'max_uses'              => 'required|integer|min:1|max:1000',
            'expires_at'            => 'nullable|date|after:today',
            'is_active'             => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $coupon->update([
            'code'                  => strtoupper(trim($request->code)),
            'title'                 => $request->title,
            'description'           => $request->description,
            'discount_amount'       => $request->discount_amount,
            'applicable_categories' => $request->applicable_categories,
            'max_uses'              => $request->max_uses,
            'expires_at'            => $request->expires_at,
            'is_active'             => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon updated successfully!');
    }

    /**
     * Delete coupon
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Toggle coupon active status
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
                         ->with('success', "Coupon has been {$status} successfully!");
    }
}