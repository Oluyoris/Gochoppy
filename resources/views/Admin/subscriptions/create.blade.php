@extends('layouts.admin')
@section('title', 'Add Subscription Plan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Add New Subscription Plan</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subscriptions.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Select Vendor</label>
                    <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                        <option value="">-- Choose Vendor --</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->vendorProfile->company_name ?? $vendor->email }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="plan_type" class="form-label">Plan Type</label>
                        <input type="text" name="plan_type" class="form-control @error('plan_type') is-invalid @enderror" value="{{ old('plan_type') }}" required placeholder="e.g. Featured, Premium">
                        @error('plan_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required min="0">
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                        <label class="form-check-label" for="is_active">Active (visible to vendor)</label>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Add Subscription Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection