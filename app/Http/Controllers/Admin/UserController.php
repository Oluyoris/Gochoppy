<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'customer')
                     ->orderBy('created_at', 'desc')
                     ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403, 'Only customers can be managed here.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'customer') {
            abort(403, 'Only customers can be managed here.');
        }

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'phone'                 => 'nullable|string|max:20',
            'address'               => 'nullable|string|max:500',
            'state'                 => 'nullable|string|max:100',
            'popular_bus_stop_id'   => 'nullable|exists:popular_bus_stops,id',   // NEW
            'is_active'             => 'boolean',
        ]);

        $user->update($request->only([
            'name', 
            'email', 
            'phone', 
            'address', 
            'state', 
            'popular_bus_stop_id',   // NEW
            'is_active'
        ]));

        return redirect()->route('admin.users.index')
                         ->with('success', 'Customer updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403, 'Only customers can be managed here.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Customer deleted successfully!');
    }

    public function toggleActive(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403, 'Only customers can be managed here.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'activated' : 'blocked';

        return redirect()->route('admin.users.index')
                         ->with('success', "User has been {$status}!");
    }

    public function transactions(User $user)
    {
        if ($user->role !== 'customer') {
            abort(403);
        }

        $transactions = Transaction::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(20);

        return view('admin.users.transactions', compact('user', 'transactions'));
    }
}