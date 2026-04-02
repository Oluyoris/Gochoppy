@extends('layouts.admin')
@section('title', 'Vendors')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="gc-page-title">Vendors</h2>
        <p class="gc-page-sub mb-0">Manage kitchens, supermarkets, and pharmacies</p>
    </div>
    <a href="{{ route('admin.vendors.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--teal-dark),var(--teal-light));border:none;color:#fff;border-radius:10px;padding:10px 20px;font-size:13.5px;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(13,148,136,.28);transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif;"
       onmouseover="this.style.boxShadow='0 6px 20px rgba(13,148,136,.4)';this.style.transform='translateY(-1px)'"
       onmouseout="this.style.boxShadow='0 4px 14px rgba(13,148,136,.28)';this.style.transform=''">
        <i class="fas fa-plus"></i> Onboard New Vendor
    </a>
</div>

{{-- Table Card --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">

    {{-- Card Header --}}
    <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:34px;height:34px;background:var(--orange-soft);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-store" style="color:var(--orange);font-size:14px;"></i>
        </div>
        <span style="font-weight:700;font-size:15px;color:var(--text);">All Vendors</span>
        <span style="margin-left:auto;font-size:12px;color:var(--muted);background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:3px 11px;font-weight:600;">
            {{ $vendors->count() }} vendor{{ $vendors->count() != 1 ? 's' : '' }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="70">Logo</th>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Popular Bus Stop</th>   {{-- NEW COLUMN --}}
                    <th>Verified</th>
                    <th>Status</th>
                    <th class="text-end" width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vendors as $vendor)
                    @php 
                        $profile = $vendor->vendorProfile; 
                    @endphp
                    <tr>
                        <td>
                            @if ($profile && $profile->logo)
                                <img src="{{ asset('storage/' . $profile->logo) }}"
                                     alt="Logo"
                                     style="width:42px;height:42px;border-radius:10px;object-fit:cover;border:1px solid var(--border);">
                            @else
                                <div style="width:42px;height:42px;background:var(--orange-soft);border-radius:10px;display:flex;align-items:center;justify-content:center;border:1px solid #fed7aa;">
                                    <i class="fas fa-store" style="color:var(--orange);font-size:15px;"></i>
                                </div>
                            @endif
                        </td>

                        <td>
                            <span style="font-weight:700;font-size:13.5px;color:var(--text);">
                                {{ $profile?->company_name ?? '<span style="color:#dc2626;">Missing Profile</span>' }}
                            </span>
                        </td>

                        <td>
                            @if ($profile && $profile->type)
                                @php
                                    $typeColors = [
                                        'kitchen'     => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047'],
                                        'supermarket' => ['bg'=>'#dbeafe','color'=>'#1d4ed8','border'=>'#93c5fd'],
                                        'pharmacy'    => ['bg'=>'#f3e8ff','color'=>'#7e22ce','border'=>'#d8b4fe'],
                                    ];
                                    $tc = $typeColors[$profile->type] ?? ['bg'=>'var(--bg)','color'=>'var(--muted)','border'=>'var(--border)'];
                                @endphp
                                <span style="background:{{ $tc['bg'] }};color:{{ $tc['color'] }};border:1px solid {{ $tc['border'] }};border-radius:20px;padding:3px 11px;font-size:11.5px;font-weight:700;">
                                    {{ ucfirst($profile->type) }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td style="font-size:13px;color:var(--text-body);">{{ $vendor->email }}</td>
                        <td style="font-size:13px;color:var(--text-body);">{{ $vendor->phone }}</td>
                        <td style="font-size:13px;color:var(--muted);max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $vendor->address ?? '—' }}</td>

                        {{-- NEW: Popular Bus Stop Column for Vendor --}}
                        <td style="font-size:13px;color:var(--text-body);">
                            @if($profile && $profile->popular_bus_stop_id && $profile->popularBusStop)
                                <span style="background:var(--teal-soft);color:var(--teal-dark);padding:4px 10px;border-radius:6px;font-size:12.5px;font-weight:600;">
                                    {{ $profile->popularBusStop->name }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td>
                            @if($profile && $profile->is_verified)
                                <span class="badge bg-success"><i class="fas fa-shield-halved me-1" style="font-size:9px;"></i>Verified</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-clock me-1" style="font-size:9px;"></i>Pending</span>
                            @endif
                        </td>

                        <td>
                            @if($vendor->is_active)
                                <span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size:7px;"></i>Active</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-ban me-1" style="font-size:9px;"></i>Blocked</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div style="display:inline-flex;align-items:center;gap:5px;">

                                {{-- Edit --}}
                                <a href="{{ route('admin.vendors.edit', $vendor) }}"
                                   title="Edit"
                                   style="width:32px;height:32px;background:var(--teal-soft);border:1px solid #99f6e4;color:var(--teal-dark);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;transition:all .18s;"
                                   onmouseover="this.style.background='var(--teal)';this.style.color='#fff';this.style.borderColor='var(--teal)'"
                                   onmouseout="this.style.background='var(--teal-soft)';this.style.color='var(--teal-dark)';this.style.borderColor='#99f6e4'">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Transactions --}}
                                <a href="{{ route('admin.vendors.transactions', $vendor) }}"
                                   title="View Transactions"
                                   style="width:32px;height:32px;background:#dbeafe;border:1px solid #93c5fd;color:#1d4ed8;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;transition:all .18s;"
                                   onmouseover="this.style.background='#2563eb';this.style.color='#fff';this.style.borderColor='#2563eb'"
                                   onmouseout="this.style.background='#dbeafe';this.style.color='#1d4ed8';this.style.borderColor='#93c5fd'">
                                    <i class="fas fa-receipt"></i>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Delete"
                                            style="width:32px;height:32px;background:#fee2e2;border:1px solid #fca5a5;color:#dc2626;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;transition:all .18s;"
                                            onmouseover="this.style.background='#dc2626';this.style.color='#fff';this.style.borderColor='#dc2626'"
                                            onmouseout="this.style.background='#fee2e2';this.style.color='#dc2626';this.style.borderColor='#fca5a5'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">   {{-- Increased colspan because we added one column --}}
                            <div style="text-align:center;padding:56px 0;">
                                <div style="width:60px;height:60px;background:var(--orange-soft);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                                    <i class="fas fa-store" style="font-size:24px;color:var(--orange-light);"></i>
                                </div>
                                <p style="font-weight:700;color:var(--text);font-size:14px;margin-bottom:4px;">No vendors onboarded yet</p>
                                <p style="color:var(--muted);font-size:13px;margin:0 0 16px;">Get started by onboarding your first vendor.</p>
                                <a href="{{ route('admin.vendors.create') }}"
                                   style="display:inline-flex;align-items:center;gap:7px;background:var(--teal);color:#fff;border-radius:9px;padding:9px 20px;font-size:13px;font-weight:700;text-decoration:none;">
                                    <i class="fas fa-plus"></i> Onboard First Vendor
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div style="padding:13px 22px;border-top:1px solid var(--border-light);background:var(--bg);font-size:12.5px;color:var(--muted);font-weight:500;text-align:center;">
        Showing {{ $vendors->count() }} vendor{{ $vendors->count() != 1 ? 's' : '' }}
    </div>

</div>

@endsection