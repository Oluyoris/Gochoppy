@extends('layouts.admin')
@section('title', 'Customers')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="gc-page-title">Customers</h2>
        <p class="gc-page-sub mb-0">Manage all registered users who order food</p>
    </div>
    <div style="background:var(--teal-soft);border:1px solid #99f6e4;border-radius:10px;padding:8px 16px;font-size:12.5px;font-weight:700;color:var(--teal-dark);">
        <i class="fas fa-users me-2"></i>Users
    </div>
</div>

{{-- Alert messages --}}
@if(session('success'))
    <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- Table Card --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">

    {{-- Card Header --}}
    <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:34px;height:34px;background:var(--teal-soft);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-users" style="color:var(--teal);font-size:14px;"></i>
        </div>
        <span style="font-weight:700;font-size:15px;color:var(--text);">All Customers</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>State</th>
                    <th>Popular Bus Stop</th>   {{-- NEW COLUMN --}}
                    <th>Status</th>
                    <th width="280" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div style="width:32px;height:32px;background:var(--teal-soft);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--teal);flex-shrink:0;">
                                    {{ strtoupper(substr($user->name ?? 'N', 0, 1)) }}
                                </div>
                                <span style="font-weight:600;font-size:13.5px;color:var(--text);">{{ $user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--text-body);">{{ $user->email }}</td>
                        <td style="font-size:13px;color:var(--text-body);">{{ $user->phone }}</td>
                        <td style="font-size:13px;color:var(--muted);max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $user->address ?? '—' }}</td>
                        <td style="font-size:13px;color:var(--text-body);">{{ $user->state ?? '—' }}</td>
                        
                        {{-- NEW: Popular Bus Stop Column --}}
                        <td style="font-size:13px;color:var(--text-body);">
                            @if($user->popularBusStop)
                                <span style="background:var(--teal-soft);color:var(--teal-dark);padding:4px 10px;border-radius:6px;font-size:12.5px;font-weight:600;">
                                    {{ $user->popularBusStop->name }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'ban' }} me-1" style="font-size:10px;"></i>
                                {{ $user->is_active ? 'Active' : 'Blocked' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap;">

                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   style="display:inline-flex;align-items:center;gap:5px;background:var(--teal-soft);border:1px solid #99f6e4;color:var(--teal-dark);border-radius:7px;padding:5px 11px;font-size:12px;font-weight:600;text-decoration:none;transition:all .18s;"
                                   onmouseover="this.style.background='var(--teal)';this.style.color='#fff';this.style.borderColor='var(--teal)'"
                                   onmouseout="this.style.background='var(--teal-soft)';this.style.color='var(--teal-dark)';this.style.borderColor='#99f6e4'">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Toggle Active --}}
                                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            style="display:inline-flex;align-items:center;gap:5px;background:{{ $user->is_active ? '#fef9c3' : '#dcfce7' }};border:1px solid {{ $user->is_active ? '#fde047' : '#86efac' }};color:{{ $user->is_active ? '#854d0e' : '#166534' }};border-radius:7px;padding:5px 11px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .18s;">
                                        {{ $user->is_active ? 'Block' : 'Activate' }}
                                    </button>
                                </form>

                                {{-- Transactions --}}
                                <a href="{{ route('admin.users.transactions', $user) }}"
                                   style="display:inline-flex;align-items:center;gap:5px;background:#dbeafe;border:1px solid #93c5fd;color:#1d4ed8;border-radius:7px;padding:5px 11px;font-size:12px;font-weight:600;text-decoration:none;transition:all .18s;"
                                   onmouseover="this.style.background='#2563eb';this.style.color='#fff';this.style.borderColor='#2563eb'"
                                   onmouseout="this.style.background='#dbeafe';this.style.color='#1d4ed8';this.style.borderColor='#93c5fd'">
                                    <i class="fas fa-receipt"></i> Transactions
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this customer permanently? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="display:inline-flex;align-items:center;gap:5px;background:#fee2e2;border:1px solid #fca5a5;color:#dc2626;border-radius:7px;padding:5px 11px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .18s;"
                                            onmouseover="this.style.background='#dc2626';this.style.color='#fff';this.style.borderColor='#dc2626'"
                                            onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626';this.style.borderColor='#fca5a5'">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">   {{-- Changed from 7 to 8 because we added one column --}}
                            <div style="text-align:center;padding:52px 0;">
                                <div style="width:56px;height:56px;background:var(--teal-soft);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                    <i class="fas fa-users" style="font-size:22px;color:var(--teal-light);"></i>
                                </div>
                                <p style="font-weight:700;color:var(--text);font-size:14px;margin-bottom:4px;">No customers yet</p>
                                <p style="color:var(--muted);font-size:13px;margin:0;">Customers will appear here once they register.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:14px 20px;border-top:1px solid var(--border-light);background:var(--bg);">
        {{ $users->links() }}
    </div>

</div>

@endsection