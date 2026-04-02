@extends('layouts.admin')

@section('title', 'Edit Intervals - ' . $busStop->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h1>Delivery Intervals From: <strong>{{ $busStop->name }}</strong></h1>
        <a href="{{ route('admin.bus-stops.index') }}" class="btn btn-secondary">← Back to List</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.bus-stops.update', $busStop) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h5>Set Price & Estimated Time to Other Bus Stops</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>To Bus Stop</th>
                            <th>Price (₦)</th>
                            <th>Estimated Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervals as $interval)
                            <tr>
                                <td><strong>{{ $interval->toStop->name }}</strong></td>
                                <td>
                                    <input type="number" 
                                           name="intervals[{{ $interval->id }}][price]" 
                                           value="{{ $interval->price }}" 
                                           class="form-control" min="500" required>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="intervals[{{ $interval->id }}][estimated_time]" 
                                           value="{{ $interval->estimated_time }}" 
                                           class="form-control" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save All Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection