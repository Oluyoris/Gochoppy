@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary">
            ← Back to Deposits
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Deposit Details - {{ $deposit->reference }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <h5 class="text-muted mb-3">Customer Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Customer Name</th>
                            <td>{{ $deposit->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $deposit->user->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $deposit->user->email ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    <h5 class="text-muted mt-4 mb-3">Deposit Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Reference</th>
                            <td><strong>{{ $deposit->reference }}</strong></td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td><strong class="text-success">₦{{ number_format($deposit->amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $deposit->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($deposit->status === 'pending')
                                    <span class="badge bg-warning">Pending Approval</span>
                                @elseif($deposit->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted On</th>
                            <td>{{ $deposit->created_at->format('d M, Y \a\t H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Right Column - Proof -->
                <div class="col-md-6">
                    <h5 class="text-muted mb-3">Payment Proof</h5>
                    @if($deposit->proof)
                        <div class="border p-3 text-center bg-light">
                            <img src="{{ Storage::url($deposit->proof) }}" 
                                 alt="Payment Proof" 
                                 class="img-fluid" 
                                 style="max-height: 450px; border: 2px solid #ddd;">
                            <p class="mt-3">
                                <a href="{{ Storage::url($deposit->proof) }}" 
                                   target="_blank" 
                                   class="btn btn-info">
                                    <i class="fas fa-download"></i> Open Full Image
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No proof image was uploaded for this deposit.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-footer">
            @if($deposit->status === 'pending')
                <form action="{{ route('admin.deposits.approve', $deposit) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg px-5" 
                            onclick="return confirm('Are you sure you want to APPROVE this deposit and credit the customer wallet?')">
                        ✅ Approve & Credit Wallet
                    </button>
                </form>

                <form action="{{ route('admin.deposits.reject', $deposit) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg px-5" 
                            onclick="return confirm('Are you sure you want to REJECT this deposit?')">
                        ❌ Reject Deposit
                    </button>
                </form>
            @else
                <div class="alert alert-info">
                    This deposit has already been {{ $deposit->status }}.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection