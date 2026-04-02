<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class MenuRequestController extends Controller
{
    public function index()
    {
        $requests = MenuRequest::with('vendor')
                               ->orderBy('status')
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);

        return view('admin.menu-requests.index', compact('requests'));
    }

    public function approve(MenuRequest $menuRequest)
    {
        if ($menuRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request already processed.');
        }

        // Create actual Item in vendor's menu
        Item::create([
            'vendor_id'     => $menuRequest->vendor_id,
            'vendor_type'   => $menuRequest->vendor->vendorProfile->type,
            'name'          => $menuRequest->name,
            'description'   => $menuRequest->description,
            'price'         => $menuRequest->price,
            'image'         => $menuRequest->image,
            'category'      => 'Uncategorized', // can be set later
            'is_available'  => true,
        ]);

        $menuRequest->update([
            'status' => 'approved',
        ]);

        return redirect()->route('admin.menu-requests.index')
                         ->with('success', 'Menu request approved and added to vendor menu!');
    }

    public function reject(Request $request, MenuRequest $menuRequest)
    {
        if ($menuRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request already processed.');
        }

        $menuRequest->update([
            'status' => 'rejected',
        ]);

        // Optional: store rejection reason if you add a textarea later

        return redirect()->route('admin.menu-requests.index')
                         ->with('success', 'Menu request rejected.');
    }
}