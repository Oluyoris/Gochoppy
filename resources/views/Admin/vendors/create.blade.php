@extends('layouts.admin')
@section('title', 'Onboard New Vendor')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.vendors.index') }}"
       style="width:36px;height:36px;background:#fff;border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;flex-shrink:0;transition:all .18s;"
       onmouseover="this.style.borderColor='var(--teal)';this.style.color='var(--teal)'"
       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left" style="font-size:13px;"></i>
    </a>
    <div>
        <h2 class="gc-page-title mb-0">Onboard New Vendor</h2>
        <p class="gc-page-sub mb-0">Fill in the details to register a new vendor</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.vendors.store') }}" enctype="multipart/form-data">
@csrf

{{-- Section 1: Business Info --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:var(--teal-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-building" style="color:var(--teal);font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Business Information</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">

            <div class="col-md-6">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" name="company_name" id="company_name"
                       class="form-control @error('company_name') is-invalid @enderror"
                       value="{{ old('company_name') }}" required>
                @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="type" class="form-label">Vendor Type</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="kitchen"     {{ old('type') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                    <option value="supermarket" {{ old('type') == 'supermarket' ? 'selected' : '' }}>Supermarket</option>
                    <option value="pharmacy"    {{ old('type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="logo" class="form-label">Company Logo</label>
                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/png,image/jpeg">
                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- NEW: Popular Bus Stop --}}
            <div class="col-md-6">
                <label for="popular_bus_stop_id" class="form-label">
                    <i class="fas fa-map-marker-alt me-1" style="color:var(--teal);"></i>
                    Popular Bus Stop (Pickup Location)
                </label>
                <select name="popular_bus_stop_id" id="popular_bus_stop_id" 
                        class="form-select @error('popular_bus_stop_id') is-invalid @enderror">
                    <option value="">Select Bus Stop</option>
                    @foreach(\App\Models\PopularBusStop::orderBy('name')->get() as $stop)
                        <option value="{{ $stop->id }}" {{ old('popular_bus_stop_id') == $stop->id ? 'selected' : '' }}>
                            {{ $stop->name }}
                        </option>
                    @endforeach
                </select>
                @error('popular_bus_stop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">This is where dispatch will pick up orders from this vendor.</small>
            </div>

        </div>
    </div>
</div>

{{-- Rest of the form remains the same (Contact & Login, Bank Details) --}}
{{-- Section 2: Contact & Login --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-id-card" style="color:#2563eb;font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Contact & Login Details</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
    </div>
</div>

{{-- Section 3: Bank Details --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:var(--orange-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-building-columns" style="color:var(--orange);font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Bank Details</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">
            <div class="col-md-4">
                <label for="bank_name" class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" required>
                @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="account_number" class="form-label">Account Number</label>
                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" required>
                @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="account_name" class="form-label">Account Name</label>
                <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name') }}" required>
                @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<div style="display:flex;align-items:center;gap:10px;">
    <button type="submit" class="btn btn-primary">Onboard Vendor</button>
    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection