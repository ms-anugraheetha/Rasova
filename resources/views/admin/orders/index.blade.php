@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

<h1>Orders</h1>

<form method="GET" style="display:flex;gap:12px;margin-bottom:20px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order number..." class="admin-input" style="flex:1;">

    <select name="status" class="admin-select" style="max-width:200px;">
        <option value="">All statuses</option>
        @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'stock_issue'] as $status)
            <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary" style="min-height:44px;padding:0 20px;">Filter</button>
</form>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user?->full_name ?? $order->guest_email ?? 'Guest' }}</td>
                    <td>{{ ucfirst($order->order_status) }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>&#8377;{{ number_format($order->total_minor / 100, 2) }}</td>
                    <td>{{ $order->created_at->format('M j, Y') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order->id) }}" class="admin-btn-link">View</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="opacity:0.6;">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $orders->links() }}
</div>

@endsection