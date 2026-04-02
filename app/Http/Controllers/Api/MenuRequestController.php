<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuRequestController extends Controller
{
    /**
     * Vendor submits a new menu item request
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can submit menu requests.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category'    => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $menuRequest = MenuRequest::create([
            'vendor_id'   => $user->id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'category'    => $request->category,
            'status'      => 'pending',
        ]);

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('menu-requests', 'public');
            $menuRequest->update(['image' => $path]);
        }

        return response()->json([
            'success'      => true,
            'message'      => 'Menu request submitted successfully – awaiting admin approval',
            'menu_request' => $menuRequest,
        ], 201);
    }

    /**
     * Get all menu requests submitted by the authenticated vendor
     */
    public function myRequests(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can view their menu requests.',
            ], 403);
        }

        $requests = MenuRequest::where('vendor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'id'          => $req->id,
                    'name'        => $req->name,
                    'description' => $req->description,
                    'price'       => (float) $req->price,
                    'category'    => $req->category,
                    'image'       => $req->image ? asset('storage/' . $req->image) : null,
                    'status'      => $req->status,
                    'created_at'  => $req->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success'  => true,
            'requests' => $requests,
        ]);
    }
}