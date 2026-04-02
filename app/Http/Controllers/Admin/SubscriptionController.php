<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorSubscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = VendorSubscription::with('vendor')
                                          ->orderBy('end_date', 'desc')
                                          ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $vendors = User::where('role', 'vendor')->get(['id', 'name']);

        return view('admin.subscriptions.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id'     => 'required|exists:users,id',
            'plan_type'     => 'required|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'is_active'     => 'boolean',
        ]);

        VendorSubscription::create([
            'vendor_id'   => $validated['vendor_id'],
            'plan_type'   => $validated['plan_type'],
            'amount'      => $validated['amount'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription plan added successfully!');
    }

    public function edit(VendorSubscription $subscription)
    {
        $subscription->load('vendor');
        $vendors = User::where('role', 'vendor')->get(['id', 'name']);

        return view('admin.subscriptions.edit', compact('subscription', 'vendors'));
    }

    public function update(Request $request, VendorSubscription $subscription)
    {
        $validated = $request->validate([
            'vendor_id'     => 'required|exists:users,id',
            'plan_type'     => 'required|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'is_active'     => 'boolean',
        ]);

        $subscription->update([
            'vendor_id'   => $validated['vendor_id'],
            'plan_type'   => $validated['plan_type'],
            'amount'      => $validated['amount'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'is_active'   => $request->boolean('is_active', $subscription->is_active),
        ]);

        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(VendorSubscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
                         ->with('success', 'Subscription plan deleted!');
    }
}