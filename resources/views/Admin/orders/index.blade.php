@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">All Orders</h2>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Vendor</th>
                            <th>Dispatcher</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Placed</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->vendor->vendorProfile->company_name ?? $order->vendor->email ?? 'N/A' }}</td>
                                <td>{{ $order->dispatcher ? $order->dispatcher->name : 'Not assigned' }}</td>
                                <td>₦{{ number_format($order->grand_total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
       