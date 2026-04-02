
@extends('layouts.admin')
@section('title', 'All Transactions')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">All Transactions</h2>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.transactions.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search description or user" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit Only</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option value="">All Users</option>
                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customers (Debits)</option>
                            <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendors</option>
                            <option value="dispatcher" {{ request('role') == 'dispatcher' ? 'selected' : '' }}>Dispatchers</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="related" class="form-select">
                            <option value="">All Transactions</option>
                            <option value="order" {{ request('related') == 'order' ? 'selected' : '' }}>Only Order Related</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>User (Role)</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    {{ $tx->user ? $tx->user->name : 'N/A' }}
                                    <small class="text-muted d-block">
                                        ({{ ucfirst($tx->user?->role ?? 'Unknown') }})
                                    </small>
                                </td>
                                <td>{{ $tx->description }}</td>
                                <td>
                                    <span class="badge bg-{{ $tx->type == 'credit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($tx->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold {{ $tx->type == 'debit' ? 'text-danger' : 'text-success' }}">
                                    {{ $tx->type == 'debit' ? '-' : '+' }} ₦{{ number_format($tx->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">No transactions found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection