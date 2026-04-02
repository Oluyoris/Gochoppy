@extends('layouts.admin')
@section('title', 'Customer Transactions')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.users.index') }}"
       style="width:36px;height:36px;background:#fff;border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;flex-shrink:0;transition:all .18s;"
       onmouseover="this.style.borderColor='var(--teal)';this.style.color='var(--teal)'"
       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left" style="font-size:13px;"></i>
    </a>
    <div>
        <h2 class="gc-page-title mb-0">Customer Transactions</h2>
        <p class="gc-page-sub mb-0">
            Viewing transactions for
            <span style="color:var(--teal);font-weight:700;">{{ $user->name ?? $user->email }}</span>
        </p>
    </div>
</div>

{{-- User Summary Strip --}}
<div style="background:linear-gradient(135deg,var(--teal-dark) 0%,var(--teal-light) 100%);border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;gap:16px;box-shadow:0 6px 20px rgba(13,148,136,.25);position:relative;overflow:hidden;">
    <div style="position:absolute;right:-20px;bottom:-20px;width:100px;height:100px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
    <div style="width:48px;height:48px;background:rgba(255,255,255,.25);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;font-family:'Plus Jakarta Sans',sans-serif;flex-shrink:0;">
        {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
    </div>
    <div>
        <div style="font-weight:800;font-size:15px;color:#fff;margin-bottom:2px;">{{ $user->name ?? 'N/A' }}</div>
        <div style="font-size:12.5px;color:rgba(255,255,255,.72);">
            <i class="fas fa-envelope me-1"></i>{{ $user->email }}
            @if($user->phone)
                &nbsp;&bull;&nbsp;<i class="fas fa-phone me-1"></i>{{ $user->phone }}
            @endif
        </div>
    </div>
</div>

{{-- Table Card --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">

    {{-- Card Header --}}
    <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;background:var(--orange-soft);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-receipt" style="color:var(--orange);font-size:14px;"></i>
            </div>
            <span style="font-weight:700;font-size:15px;color:var(--text);">Transaction History</span>
        </div>
        <span style="font-size:11.5px;color:var(--muted);background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:4px 11px;font-weight:600;">
            All records
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $tx)
                    <tr>
                        <td style="font-size:13px;color:var(--text-body);white-space:nowrap;">
                            <i class="fas fa-clock me-1" style="color:var(--muted);font-size:11px;"></i>
                            {{ $tx->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="font-size:13px;color:var(--text-body);max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $tx->description }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $tx->type == 'credit' ? 'success' : 'danger' }}">
                                <i class="fas fa-{{ $tx->type == 'credit' ? 'arrow-up' : 'arrow-down' }} me-1" style="font-size:9px;"></i>
                                {{ ucfirst($tx->type) }}
                            </span>
                        </td>
                        <td style="font-weight:700;font-size:14px;color:{{ $tx->type == 'credit' ? 'var(--teal-dark)' : '#dc2626' }};">
                            {{ $tx->type == 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div style="text-align:center;padding:52px 0;">
                                <div style="width:56px;height:56px;background:var(--orange-soft);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                    <i class="fas fa-receipt" style="font-size:22px;color:var(--orange-light);"></i>
                                </div>
                                <p style="font-weight:700;color:var(--text);font-size:14px;margin-bottom:4px;">No transactions found</p>
                                <p style="color:var(--muted);font-size:13px;margin:0;">This customer hasn't made any transactions yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:14px 20px;border-top:1px solid var(--border-light);background:var(--bg);">
        {{ $transactions->links() }}
    </div>

</div>

@endsection