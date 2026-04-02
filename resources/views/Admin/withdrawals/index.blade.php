@extends('layouts.admin')
@section('title', 'Withdrawals')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Withdrawal Requests</h2>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Bank Details</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Processed</th>
                            <th width="220" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdrawals as $wd)
                            <tr>
                                <td>{{ $wd->id }}</td>
                                <td>{{ $wd->wallet->user->name ?? 'N/A' }}</td>
                                <td>₦{{ number_format($wd->amount, 2) }}</td>
                                <td>
                                    {{ $wd->bank_name }}<br>
                                    {{ $wd->account_number }}<br>
                                    {{ $wd->account_name }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $wd->status == 'pending' ? 'warning' : ($wd->status == 'completed' ? 'success' : 'danger') }}">
                                        {{ ucfirst($wd->status) }}
                                    </span>
                                </td>
                                <td>{{ $wd->created_at->diffForHumans() }}</td>
                                <td>{{ $wd->processed_at ? $wd->processed_at->diffForHumans() : '—' }}</td>
                                <td class="text-end">
                                    @if ($wd->status == 'pending')
                                        <form action="{{ route('admin.withdrawals.approve', $wd) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this withdrawal?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.withdrawals.reject', $wd) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this withdrawal?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">No withdrawal requests yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection