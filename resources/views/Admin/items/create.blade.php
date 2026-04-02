@extends('layouts.admin')
@section('title', 'Add Item')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Add New Item</h2>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vendor <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                            <option value="">Select Vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }} ({{ ucfirst($vendor->role) }})
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Category/Type <span class="text-danger">*</span></label>
                        <select name="vendor_type" class="form-select @error('vendor_type') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="kitchen" {{ old('vendor_type') == 'kitchen' ? 'selected' : '' }}>Kitchen / Food</option>
                            <option value="supermarket" {{ old('vendor_type') == 'supermarket' ? 'selected' : '' }}>Supermarket / Groceries</option>
                            <option value="pharmacy" {{ old('vendor_type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy / Drugs</option>
                        </select>
                        @error('vendor_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (₦) <span class="text-danger">*</span></label>
                        <input type="number" name="price" step="0.01" min="0" 
                               class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price') }}" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sub-Category (optional)</label>
                        <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                               value="{{ old('category') }}" placeholder="e.g. Rice, Beverages, Pain Relief">
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="is_available" checked>
                            <label class="form-check-label" for="is_available">Available for order</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Create Item</button>
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection