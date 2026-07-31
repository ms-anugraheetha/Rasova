@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h1>Dashboard</h1>

<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <p>Total Orders</p>
        <p>{{ $totalOrders }}</p>
    </div>
    <div class="admin-stat-card">
        <p>Pending Orders</p>
        <p>{{ $pendingOrders }}</p>
    </div>
    <div class="admin-stat-card">
        <p>Revenue (Paid)</p>
        <p>&#8377;{{ number_format($totalRevenue / 100, 2) }}</p>
    </div>
</div>

<div class="admin-card" style="margin-bottom:24px;">
    <h2>Orders — Last 7 Days</h2>
    <canvas id="ordersChart" height="80"></canvas>
</div>

<div class="admin-card">
    <h2>Recent Orders</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentOrders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user?->full_name ?? $order->guest_email ?? 'Guest' }}</td>
                    <td>{{ ucfirst($order->order_status) }}</td>
                    <td>&#8377;{{ number_format($order->total_minor / 100, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="opacity:0.6;">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('ordersChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($ordersPerDay->pluck('date')) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($ordersPerDay->pluck('count')) !!},
                borderColor: '#b3132d',
                backgroundColor: 'rgba(179, 19, 45, 0.1)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endpush