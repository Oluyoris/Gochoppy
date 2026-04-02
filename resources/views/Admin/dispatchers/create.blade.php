@extends('layouts.admin')
@section('title', 'Onboard New Dispatcher')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Onboard New Dispatcher</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dispatchers.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                <div class="row">
                   <div class="col-md-6 mb-3">
                     <label for="password" class="form-label">Password</label>
                     <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                      @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     <div class="col-md-6 mb-3">
                      <label for="password_confirmation" class="form-label">Confirm Password</label>
                      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
                      @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                   </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="plate_number" class="form-label">Plate Number</label>
                        <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror" value="{{ old('plate_number') }}" required>
                        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nin_number" class="form-label">NIN Number</label>
                        <input type="text" name="nin_number" class="form-control @error('nin_number') is-invalid @enderror" value="{{ old('nin_number') }}" required>
                        @error('nin_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grantor_name" class="form-label">Grantor Name</label>
                        <input type="text" name="grantor_name" class="form-control @error('grantor_name') is-invalid @enderror" value="{{ old('grantor_name') }}" required>
                        @error('grantor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grantor_address" class="form-label">Grantor Address</label>
                        <textarea name="grantor_address" class="form-control @error('grantor_address') is-invalid @enderror" rows="2" required>{{ old('grantor_address') }}</textarea>
                        @error('grantor_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="avatar" class="form-label">Dispatcher Photo (optional)</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grantor_image" class="form-label">Grantor ID/Image (optional)</label>
                        <input type="file" name="grantor_image" class="form-control @error('grantor_image') is-invalid @enderror" accept="image/*">
                        @error('grantor_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>
                <h5>Bank Details (for payouts)</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" required>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" required>
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name') }}" required>
                        @error('account_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Onboard Dispatcher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsectionss