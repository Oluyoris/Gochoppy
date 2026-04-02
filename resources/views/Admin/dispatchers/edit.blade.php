@extends('layouts.admin')
@section('title', 'Edit Dispatcher')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Dispatcher: {{ $dispatcher->dispatcherProfile->full_name ?? $dispatcher->name ?? '—' }}</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dispatchers.update', $dispatcher) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name', $dispatcher->dispatcherProfile->full_name ?? '') }}" required>
                        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $dispatcher->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $dispatcher->phone) }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>
                            {{ old('address', $dispatcher->address) }}
                        </textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="plate_number" class="form-label">Plate Number</label>
                        <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror" 
                               value="{{ old('plate_number', $dispatcher->dispatcherProfile->plate_number ?? '') }}" required>
                        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nin_number" class="form-label">NIN Number</label>
                        <input type="text" name="nin_number" class="form-control @error('nin_number') is-invalid @enderror" 
                               value="{{ old('nin_number', $dispatcher->dispatcherProfile->nin_number ?? '') }}" required>
                        @error('nin_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grantor_name" class="form-label">Grantor Name</label>
                        <input type="text" name="grantor_name" class="form-control @error('grantor_name') is-invalid @enderror" 
                               value="{{ old('grantor_name', $dispatcher->dispatcherProfile->grantor_name ?? '') }}" required>
                        @error('grantor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grantor_address" class="form-label">Grantor Address</label>
                        <textarea name="grantor_address" class="form-control @error('grantor_address') is-invalid @enderror" rows="2" required>
                            {{ old('grantor_address', $dispatcher->dispatcherProfile->grantor_address ?? '') }}
                        </textarea>
                        @error('grantor_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Images -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="avatar" class="form-label">Dispatcher Photo (leave blank to keep current)</label>
                        @if ($dispatcher->avatar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $dispatcher->avatar) }}" alt="Avatar" class="rounded-circle" width="100">
                                <p class="text-muted small">Current photo</p>
                            </div>
                        @endif
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grantor_image" class="form-label">Grantor ID/Image (leave blank to keep current)</label>
                        @if ($dispatcher->dispatcherProfile->grantor_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $dispatcher->dispatcherProfile->grantor_image) }}" alt="Grantor" width="100">
                                <p class="text-muted small">Current grantor image</p>
                            </div>
                        @endif
                        <input type="file" name="grantor_image" class="form-control @error('grantor_image') is-invalid @enderror" accept="image/*">
                        @error('grantor_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>
                <h5>Bank Details (for payouts)</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $dispatcher->dispatcherProfile->bank_name ?? '') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $dispatcher->dispatcherProfile->account_number ?? '') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $dispatcher->dispatcherProfile->account_name ?? '') }}" required>
                    </div>
                </div>

                <hr>
                <h5>Status Controls</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $dispatcher->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active (can accept jobs)</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Update Dispatcher</button>
                </div>
            </form>

            <hr class="my-4">

            <form action="{{ route('admin.dispatchers.destroy', $dispatcher) }}" method="POST" onsubmit="return confirm('Delete this dispatcher? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Delete Dispatcher
                </button>
            </form>
        </div>
    </div>
</div>
@endsection