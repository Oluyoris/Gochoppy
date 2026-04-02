<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendors = User::where('role', 'vendor')
                       ->with([
                           'vendorProfile',                    // Load profile
                           'vendorProfile.popularBusStop'      // ← IMPORTANT: Load nested relationship
                       ])
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'        => 'required|string|max:255',
            'type'                => ['required', Rule::in(['kitchen', 'supermarket', 'pharmacy'])],
            'email'               => 'required|email|unique:users,email',
            'phone'               => 'required|string|max:20|unique:users,phone',
            'password'            => 'required|string|min:8|confirmed',
            'address'             => 'required|string',
            'bank_name'           => 'required|string|max:100',
            'account_number'      => 'required|string|max:50',
            'account_name'        => 'required|string|max:100',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'popular_bus_stop_id' => 'nullable|exists:popular_bus_stops,id',   // ← NEW (optional)
        ]);

        DB::beginTransaction();

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            }

            $user = User::create([
                'name'      => $validated['company_name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'password'  => Hash::make($validated['password']),
                'role'      => 'vendor',
                'address'   => $validated['address'],
                'is_active' => true,
            ]);

            VendorProfile::create([
                'user_id'             => $user->id,
                'company_name'        => $validated['company_name'],
                'type'                => $validated['type'],
                'logo'                => $logoPath,
                'address'             => $validated['address'],
                'bank_name'           => $validated['bank_name'],
                'account_number'      => $validated['account_number'],
                'account_name'        => $validated['account_name'],
                'is_verified'         => false,
                'popular_bus_stop_id' => $validated['popular_bus_stop_id'] ?? null,   // ← NEW
            ]);

            DB::commit();

            return redirect()->route('admin.vendors.index')
                             ->with('success', 'Vendor onboarded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to onboard vendor: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a vendor.
     */
    public function edit(User $vendor)
    {
        $vendor->load([
            'vendorProfile',
            'vendorProfile.popularBusStop'   // ← Also good to load here
        ]);

        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, User $vendor)
    {
        $validated = $request->validate([
            'company_name'        => 'required|string|max:255',
            'type'                => ['required', Rule::in(['kitchen', 'supermarket', 'pharmacy'])],
            'email'               => 'required|email|' . Rule::unique('users')->ignore($vendor->id),
            'phone'               => 'required|string|max:20|' . Rule::unique('users')->ignore($vendor->id),
            'password'            => 'nullable|string|min:8|confirmed',
            'address'             => 'required|string',
            'bank_name'           => 'required|string|max:100',
            'account_number'      => 'required|string|max:50',
            'account_name'        => 'required|string|max:100',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'           => 'boolean',
            'is_verified'         => 'boolean',
            'popular_bus_stop_id' => 'nullable|exists:popular_bus_stops,id',   // ← NEW
        ]);

        DB::beginTransaction();

        try {
            // Update user basic info
            $userData = [
                'name'      => $validated['company_name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'address'   => $validated['address'],
                'is_active' => $request->boolean('is_active', false),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $vendor->update($userData);

            // Prepare profile data
            $profileData = [
                'company_name'        => $validated['company_name'],
                'type'                => $validated['type'],
                'address'             => $validated['address'],
                'bank_name'           => $validated['bank_name'],
                'account_number'      => $validated['account_number'],
                'account_name'        => $validated['account_name'],
                'is_verified'         => $request->boolean('is_verified', false),
                'popular_bus_stop_id' => $validated['popular_bus_stop_id'] ?? null,   // ← NEW
            ];

            // Handle logo replacement
            if ($request->hasFile('logo')) {
                if ($vendor->vendorProfile?->logo) {
                    Storage::disk('public')->delete($vendor->vendorProfile->logo);
                }
                $newLogoPath = $request->file('logo')->store('vendors/logos', 'public');
                $profileData['logo'] = $newLogoPath;
            }

            // Update or create profile
            $vendor->vendorProfile()->updateOrCreate(
                ['user_id' => $vendor->id],
                $profileData
            );

            DB::commit();

            return redirect()->route('admin.vendors.index')
                             ->with('success', 'Vendor updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update vendor: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(User $vendor)
    {
        DB::beginTransaction();

        try {
            if ($vendor->vendorProfile) {
                if ($vendor->vendorProfile->logo) {
                    Storage::disk('public')->delete($vendor->vendorProfile->logo);
                }
                $vendor->vendorProfile()->delete();
            }

            $vendor->delete();

            DB::commit();

            return redirect()->route('admin.vendors.index')
                             ->with('success', 'Vendor deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to delete vendor: ' . $e->getMessage());
        }
    }

    /**
     * Show transactions for a specific vendor.
     */
    public function transactions(User $vendor)
    {
        $transactions = $vendor->transactions()
                               ->latest()
                               ->paginate(20);

        return view('admin.vendors.transactions', compact('vendor', 'transactions'));
    }
}