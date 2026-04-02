@extends('layouts.admin')  

@section('title', 'Manage Coupons')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Coupons Management</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Coupon
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Discount</th>
                        <th>Categories</th>
                        <th>Uses</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td>{{ $coupon->title }}</td>
                            <td>₦{{ number_format($coupon->discount_amount, 2) }}</td>
                            <td>
                                @foreach($coupon->applicable_categories as $cat)
                                    <span class="badge bg-info me-1">{{ ucfirst($cat) }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $coupon->used_count }} / {{ $coupon->max_uses }}
                            </td>
                            <td>
                                @if($coupon->expires_at)
                                    {{ $coupon->expires_at->format('d M, Y') }}
                                @else
                                    <span class="text-success">Never</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.coupons.toggle-status', $coupon) }}" 
                                   class="btn btn-sm {{ $coupon->is_active ? 'btn-secondary' : 'btn-success' }}">
                                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this coupon?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No coupons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection