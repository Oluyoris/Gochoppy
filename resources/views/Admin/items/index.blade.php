@extends('layouts.admin')
@section('title', 'Items')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Items Management</h2>
        <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Item
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <!-- Optional search/filter -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or category..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="vendor_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="kitchen" {{ request('vendor_type') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                            <option value="supermarket" {{ request('vendor_type') == 'supermarket' ? 'selected' : '' }}>Supermarket</option>
                            <option value="pharmacy" {{ request('vendor_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Vendor</th>
                            <th>Available</th>
                            <th width="180" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    @if ($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                                             style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category ?? '—' }}</td>
                                <td>₦{{ number_format($item->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->vendor_type == 'kitchen' ? 'warning' : ($item->vendor_type == 'pharmacy' ? 'danger' : 'info') }}">
                                        {{ ucfirst($item->vendor_type) }}
                                    </span>
                                </td>
                                <td>{{ $item->vendor->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_available ? 'success' : 'danger' }}">
                                        {{ $item->is_available ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this item? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection