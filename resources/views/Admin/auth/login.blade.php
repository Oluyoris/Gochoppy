@extends('layouts.admin-auth')

@section('title', 'Login')

@section('content')

<div class="card login-card shadow-lg">

    {{-- Card Header --}}
    <div class="card-header text-center">
        <h4>Admin Login</h4>
        <p>Sign in to access your dashboard</p>
    </div>

    {{-- Card Body --}}
    <div class="card-body p-5">

        @if (session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="fas fa-circle-exclamation me-2"></i>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-grid mt-2">
                <button type="submit" class="btn-signin">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </div>

        </form>

    </div>
</div>

@endsection