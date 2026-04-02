@extends('layouts.admin')

@section('title', 'Popular Bus Stops - Interval Control')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Popular Bus Stops & Delivery Intervals</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBusStopModal">
            + Add New Bus Stop
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bus Stops List -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Bus Stops List (Click any to edit intervals)</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bus Stop Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($busStops as $stop)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('admin.bus-stops.show', $stop) }}" class="text-primary fw-bold">
                                    {{ $stop->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.bus-stops.show', $stop) }}" class="btn btn-sm btn-warning">
                                    Edit Intervals
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">No bus stops added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delivery Fee Split Settings -->
    <div class="card">
        <div class="card-header">
            <h5>Delivery Fee Split (Dispatch vs Admin)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.delivery-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Dispatch Rider Percentage (%)</label>
                        <input type="number" 
                               name="dispatch_percentage" 
                               class="form-control form-control-lg" 
                               value="{{ $settings->dispatch_percentage ?? 60 }}" 
                               min="0" 
                               max="100" 
                               required>
                        <small class="text-muted">Percentage of delivery fee that goes to dispatcher</small>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold">Admin Percentage (%)</label>
                        <input type="text" 
                               class="form-control form-control-lg bg-light" 
                               value="{{ $settings->admin_percentage ?? 40 }}" 
                               readonly>
                        <small class="text-muted">Admin automatically gets the remaining + 100% service charge</small>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-save"></i> Save Split
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Add New Bus Stop Modal --}}
<div class="modal fade" id="addBusStopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bus-stops.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Popular Bus Stop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bus Stop Name (e.g. IRONA, OSHODI)</label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               required 
                               placeholder="Enter bus stop name" 
                               style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Bus Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection