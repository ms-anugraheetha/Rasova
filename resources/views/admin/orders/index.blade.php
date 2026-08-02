@extends('layouts.admin')

@section('title', 'Orders')

@section('extra-styles')
.admin-filter-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.admin-filter-row input, .admin-filter-row select { min-height: 40px; }
.admin-status-pill { font-size: 12px; padding: 3px 10px; border-radius: 20px; background: var(--color-accent-2-100); text-transform: capitalize; }
.admin-status-pill.delivered { background: color-mix(in srgb, green 15%, transparent); color: green; }
.admin-status-pill.cancelled, .admin-status-pill.stock_issue, .admin-status-pill.failed { background: color-mix(in srgb, var(--color-error, #b3132d) 12%, transparent); color: var(--color-error, #b3132d); }
.admin-payment-pill { font-size: 11px; padding: 2px 9px; border-radius: 20px; text-transform: capitalize; display: inline-flex; align-items: center; gap: 5px; }
.admin-payment-pill.paid { background: color-mix(in srgb, green 12%, transparent); color: green; }
.admin-payment-pill.pending { background: color-mix(in srgb, #b8860b 12%, transparent); color: #8a6d00; }
.admin-payment-pill.failed { background: color-mix(in srgb, var(--color-error, #b3132d) 12%, transparent); color: var(--color-error, #b3132d); }
.admin-payment-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
@endsection

@section('content')

<h1>Orders</h1>

<form method="GET" class="admin-filter-row">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order number..." class="admin-input" style="flex:1;min-width:200px;">

    <select name="status" class="admin-select" style="max-width:200px;">
        <option value="">All statuses</option>
        @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'stock_issue', 'failed'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary" style="min-height:40px;padding:0 20px;">Filter</button>
</form>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Payment</th>
                <th>Order Status</th>
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
                    <td>
                        <span class="admin-payment-pill {{ $order->payment_status }}">
                            <span class="admin-payment-dot"></span>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td><span class="admin-status-pill {{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
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