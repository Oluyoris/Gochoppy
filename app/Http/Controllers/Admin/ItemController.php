<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('vendor:id,name')
                     ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('vendor_type')) {
            $query->where('vendor_type', $request->vendor_type);
        }

        $items = $query->paginate(15);

        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $vendors = User::whereIn('role', ['vendor', 'kitchen', 'supermarket', 'pharmacy'])
                       ->orderBy('name')
                       ->get(['id', 'name', 'role']);

        return view('admin.items.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id'     => 'required|exists:users,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'category'      => 'nullable|string|max:100',
            'vendor_type'   => ['required', Rule::in(['kitchen', 'supermarket', 'pharmacy'])], // ← NEW required field
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available'  => 'boolean',
        ]);

        $data = $request->only([
            'vendor_id', 'name', 'description', 'price', 'category', 'vendor_type', 'is_available'
        ]);

        $data['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Item created successfully!');
    }

    public function edit(Item $item)
    {
        $item->load('vendor:id,name');

        $vendors = User::whereIn('role', ['vendor', 'kitchen', 'supermarket', 'pharmacy'])
                       ->orderBy('name')
                       ->get(['id', 'name', 'role']);

        return view('admin.items.edit', compact('item', 'vendors'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'vendor_id'     => 'required|exists:users,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'category'      => 'nullable|string|max:100',
            'vendor_type'   => ['required', Rule::in(['kitchen', 'supermarket', 'pharmacy'])], // ← NEW required field
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available'  => 'boolean',
        ]);

        $data = $request->only([
            'vendor_id', 'name', 'description', 'price', 'category', 'vendor_type', 'is_available'
        ]);

        $data['is_available'] = $request->boolean('is_available', true);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.items.index')
                         ->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.items.index')
                         ->with('success', 'Item deleted successfully!');
    }
}