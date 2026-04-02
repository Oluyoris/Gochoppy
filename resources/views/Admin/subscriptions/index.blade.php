@extends('layouts.admin')
@section('title', 'Vendor Subscriptions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vendor Subscriptions</h2>
        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Plan
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor</th>
                            <th>Plan Type</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Active</th>
                            <th width="180" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $sub)
                            <tr>
                                <td>{{ $sub->vendor->vendorProfile->company_name ?? $sub->vendor->email }}</td>
                                <td>{{ ucfirst($sub->plan_type) }}</td>
                                <td>₦{{ number_format($sub->amount, 2) }}</td>
                                <td>{{ $sub->start_date->format('d M Y') }}</td>
                                <td>{{ $sub->end_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $sub->is_active ? 'success' : 'danger' }}">
                                        {{ $sub->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subscriptions.edit', $sub) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.subscriptions.destroy', $sub) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subscription plan?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">No subscriptions yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection