@extends('layouts.admin')
@section('title', 'Dispatchers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dispatchers</h2>
        <a href="{{ route('admin.dispatchers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Onboard New Dispatcher
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Plate Number</th>
                            <th>NIN</th>
                            <th>Active</th>
                            <th width="220" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dispatchers as $dispatcher)
                            @php
                                $profile = $dispatcher->dispatcherProfile;
                            @endphp

                            <tr>
                                <td>
                                    @if ($profile && $profile->avatar)
                                        <img src="{{ asset('storage/' . $profile->avatar) }}" 
                                             alt="Avatar" 
                                             class="rounded-circle" 
                                             width="50" height="50" 
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                            <i class="fas fa-motorcycle text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="fw-medium">{{ $profile?->full_name ?? $dispatcher->name ?? '—' }}</td>
                                <td>{{ $dispatcher->email }}</td>
                                <td>{{ $dispatcher->phone }}</td>
                                <td>{{ $profile?->plate_number ?? '—' }}</td>
                                <td>{{ $profile?->nin_number ?? '—' }}</td>

                                <td>
                                    @if($dispatcher->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Blocked</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.dispatchers.edit', $dispatcher) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.dispatchers.destroy', $dispatcher) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm('Delete this dispatcher?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-motorcycle fa-3x mb-3 d-block text-muted"></i>
                                    No dispatchers onboarded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection