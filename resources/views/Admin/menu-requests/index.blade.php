@extends('layouts.admin')
@section('title', 'Menu Requests')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Menu Requests from Vendors</h2>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th width="220" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ $req->vendor->vendorProfile->company_name ?? $req->vendor->email }}</td>
                                <td class="fw-medium">{{ $req->name }}</td>
                                <td>₦{{ number_format($req->price, 2) }}</td>
                                <td>
                                    @if ($req->image)
                                        <img src="{{ asset('storage/' . $req->image) }}" alt="Item" width="60" class="rounded">
                                    @else
                                        No image
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $req->status == 'pending' ? 'warning' : ($req->status == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>{{ $req->created_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    @if ($req->status == 'pending')
                                        <form action="{{ route('admin.menu-requests.approve', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this menu item? It will be added to the vendor menu.')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.menu-requests.reject', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this menu request?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">No menu requests pending</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection