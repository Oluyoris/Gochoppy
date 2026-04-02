@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Customer Deposits (Pending)</h1>
        <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary">Refresh</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Proof</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $deposit)
                    <tr>
                        <td><strong>{{ $deposit->reference }}</strong></td>
                        <td>
                            {{ $deposit->user->name }} <br>
                            <small class="text-muted">{{ $deposit->user->phone }}</small>
                        </td>
                        <td><strong>₦{{ number_format($deposit->amount, 2) }}</strong></td>
                        <td>
                            @if($deposit->proof)
                                <span class="badge bg-info">Proof Uploaded</span>
                            @else
                                <span class="badge bg-secondary">No Proof</span>
                            @endif
                        </td>
                        <td>{{ $deposit->created_at->format('d M, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.deposits.show', $deposit) }}" 
                               class="btn btn-primary btn-sm mb-1">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            
                            <form action="{{ route('admin.deposits.approve', $deposit) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" 
                                        onclick="return confirm('Approve this deposit and credit wallet?')">
                                    Approve
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.deposits.reject', $deposit) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Reject this deposit?')">
                                    Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No pending deposits at the moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $deposits->links() }}
    </div>
</div>
@endsection