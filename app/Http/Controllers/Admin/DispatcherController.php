<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DispatcherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DispatcherController extends Controller
{
    public function index()
    {
        $dispatchers = User::where('role', 'dispatcher')
                           ->with('dispatcherProfile')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('admin.dispatchers.index', compact('dispatchers'));
    }

    public function create()
    {
        return view('admin.dispatchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'required|string|max:20|unique:users,phone',
            'password'          => 'required|string|min:8|confirmed',
            'address'           => 'required|string',
            'plate_number'      => 'required|string|max:50',
            'nin_number'        => 'required|string|max:20',
            'grantor_name'      => 'required|string|max:255',
            'grantor_address'   => 'required|string',
            'bank_name'         => 'required|string',
            'account_number'    => 'required|string',
            'account_name'      => 'required|string',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'grantor_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatarPath = $request->file('avatar')->store('dispatcher-avatars', 'public');
        }

        // Handle grantor image
        $grantorImagePath = null;
        if ($request->hasFile('grantor_image') && $request->file('grantor_image')->isValid()) {
            $grantorImagePath = $request->file('grantor_image')->store('grantor-images', 'public');
        }

        // Create User record
        $user = User::create([
            'name'      => $validated['full_name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'dispatcher',
            'address'   => $validated['address'],
            'state'     => 'Lagos', // change later if needed
            'is_active' => true,
            'avatar'    => $avatarPath,
        ]);

        // Create DispatcherProfile
        DispatcherProfile::create([
            'user_id'          => $user->id,
            'full_name'        => $validated['full_name'],
            'avatar'           => $avatarPath,
            'address'          => $validated['address'],
            'phone'            => $validated['phone'],
            'plate_number'     => $validated['plate_number'],
            'nin_number'       => $validated['nin_number'],
            'grantor_name'     => $validated['grantor_name'],
            'grantor_address'  => $validated['grantor_address'],
            'grantor_image'    => $grantorImagePath,
            'bank_name'        => $validated['bank_name'],
            'account_number'   => $validated['account_number'],
            'account_name'     => $validated['account_name'],
            'is_active'        => true,
        ]);

        return redirect()->route('admin.dispatchers.index')
                         ->with('success', 'Dispatcher onboarded successfully!');
    }

    public function edit(User $dispatcher)
    {
        $dispatcher->load('dispatcherProfile');
        return view('admin.dispatchers.edit', compact('dispatcher'));
    }

    public function update(Request $request, User $dispatcher)
    {
        $validated = $request->validate([
            'full_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $dispatcher->id,
            'phone'             => 'required|string|max:20|unique:users,phone,' . $dispatcher->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'address'           => 'required|string',
            'plate_number'      => 'required|string|max:50',
            'nin_number'        => 'required|string|max:20',
            'grantor_name'      => 'required|string|max:255',
            'grantor_address'   => 'required|string',
            'bank_name'         => 'required|string',
            'account_number'    => 'required|string',
            'account_name'      => 'required|string',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'grantor_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'         => 'boolean',
        ]);

        // Handle avatar update (keep old if no new upload)
        $avatarPath = $dispatcher->avatar;
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatarPath = $request->file('avatar')->store('dispatcher-avatars', 'public');
        }

        // Handle grantor image
        $grantorImagePath = $dispatcher->dispatcherProfile->grantor_image;
        if ($request->hasFile('grantor_image') && $request->file('grantor_image')->isValid()) {
            $grantorImagePath = $request->file('grantor_image')->store('grantor-images', 'public');
        }

        $dispatcher->update([
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'password'  => $request->filled('password') ? Hash::make($validated['password']) : $dispatcher->password,
            'address'   => $validated['address'],
            'is_active' => $request->boolean('is_active'),
            'avatar'    => $avatarPath,
        ]);

        $dispatcher->dispatcherProfile->update([
            'full_name'        => $validated['full_name'],
            'avatar'           => $avatarPath,
            'address'          => $validated['address'],
            'phone'            => $validated['phone'],
            'plate_number'     => $validated['plate_number'],
            'nin_number'       => $validated['nin_number'],
            'grantor_name'     => $validated['grantor_name'],
            'grantor_address'  => $validated['grantor_address'],
            'grantor_image'    => $grantorImagePath,
            'bank_name'        => $validated['bank_name'],
            'account_number'   => $validated['account_number'],
            'account_name'     => $validated['account_name'],
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.dispatchers.index')
                         ->with('success', 'Dispatcher updated successfully!');
    }

    public function destroy(User $dispatcher)
    {
        if ($dispatcher->role !== 'dispatcher') {
            abort(403);
        }

        $dispatcher->dispatcherProfile()->delete();
        $dispatcher->delete();

        return redirect()->route('admin.dispatchers.index')
                         ->with('success', 'Dispatcher deleted successfully!');
    }
}