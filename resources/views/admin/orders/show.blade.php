@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('extra-styles')
.admin-order-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; }
.admin-order-layout > div { display: flex; flex-direction: column; gap: 20px; }
.admin-line-row { display: flex; justify-content: space-between; font-size: 14px; padding: 8px 0; border-bottom: 1px solid var(--color-divider); }
.admin-line-row:last-child { border-bottom: none; }
.admin-total-row { display: flex; justify-content: space-between; font-weight: 700; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--color-divider); }
.admin-card p { font-size: 14px; margin: 0 0 4px; }
.admin-history-item { font-size: 13px; padding: 8px 0; border-bottom: 1px solid var(--color-divider); }
.admin-history-item:last-child { border-bottom: none; }
@endsection

@section('content')

<a href="{{ route('admin.orders.index') }}" class="admin-btn-link">&larr; Back to orders</a>
<h1 style="margin-top:8px;">Order #{{ $order->order_number }}</h1>

<div class="admin-order-layout">
    <div>
        <div class="admin-card">
            <h2>Items</h2>
            @foreach ($order->items as $item)
                <div class="admin-line-row">
                    <span>{{ $item->product_name }} ({{ $item->weight }}) &times;{{ $item->quantity }}</span>
                    <span>&#8377;{{ number_format($item->total_price_minor / 100, 2) }}</span>
                </div>
            @endforeach
            <div class="admin-total-row">
                <span>Total</span>
                <span>&#8377;{{ number_format($order->total_minor / 100, 2) }}</span>
            </div>
        </div>

        <div class="admin-card">
            <h2>Shipping address</h2>
            <p>{{ $order->shipping_full_name }}</p>
            <p>{{ $order->shipping_phone }}</p>
            <p>{{ $order->shipping_address_line_1 }}</p>
            @if ($order->shipping_address_line_2)
                <p>{{ $order->shipping_address_line_2 }}</p>
            @endif
            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
        </div>

        <div class="admin-card">
            <h2>Status history</h2>
            @forelse ($order->statusHistory as $history)
                <div class="admin-history-item">
                    <strong>{{ ucfirst($history->status) }}</strong>
                    <span style="opacity:0.6;"> — {{ $history->created_at->format('M j, Y g:ia') }}</span>
                    @if ($history->changedBy)
                        <span style="opacity:0.6;"> by {{ $history->changedBy->full_name }}</span>
                    @endif
                </div>
            @empty
                <p style="opacity:0.6;">No status changes recorded.</p>
            @endforelse
        </div>
    </div>

    <div>
        <div class="admin-card">
            <h2>Update status</h2>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf
                @method('PATCH')
                <select name="order_status" class="admin-select" style="margin-bottom:12px;">
                    @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'stock_issue'] as $status)
                        <option value="{{ $status }}" @selected($order->order_status == $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary" style="width:100%;min-height:44px;">Update</button>
            </form>
        </div>

        <div class="admin-card">
            <h2>Payment</h2>
            <p>Status: {{ ucfirst($order->payment_status) }}</p>
            @if ($order->payment)
                <p>Method: {{ ucfirst($order->payment->payment_method) }}</p>
                <p>Gateway ID: {{ $order->payment->gateway_order_id }}</p>
            @endif
        </div>
    </div>
</div>

@endsection