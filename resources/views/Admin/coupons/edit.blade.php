@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Coupon: {{ $coupon->code }}</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Same fields as create --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" 
                               value="{{ old('code', $coupon->code) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount Amount (₦) <span class="text-danger">*</span></label>
                        <input type="number" name="discount_amount" class="form-control" 
                               value="{{ old('discount_amount', $coupon->discount_amount) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" 
                           value="{{ old('title', $coupon->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Applicable Categories <span class="text-danger">*</span></label>
                    <div class="row">
                        @foreach(['kitchen', 'supermarket', 'pharmacy', 'dispatch'] as $category)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="applicable_categories[]" value="{{ $category }}"
                                           {{ in_array($category, old('applicable_categories', $coupon->applicable_categories ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ ucfirst($category) }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Uses <span class="text-danger">*</span></label>
                        <input type="number" name="max_uses" class="form-control" 
                               value="{{ old('max_uses', $coupon->max_uses) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control" 
                               value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" 
                               {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Update Coupon</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection