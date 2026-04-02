@extends('layouts.admin')
@section('title', 'Edit Sub Admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Sub Admin: {{ $subAdmin->name }}</h2>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sub-admins.update', $subAdmin) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $subAdmin->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $subAdmin->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Assigned Roles</label>
                    <div class="row">
                        @foreach ($roles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                           class="form-check-input" id="role-{{ $role->id }}"
                                           {{ in_array($role->name, $currentRoles) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Update Sub Admin</button>
                </div>
            </form>

            <hr class="my-4">

            <form action="{{ route('admin.sub-admins.destroy', $subAdmin) }}" method="POST" onsubmit="return confirm('Delete this sub-admin? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Delete Sub Admin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection