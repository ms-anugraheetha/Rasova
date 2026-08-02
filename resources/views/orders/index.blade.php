@extends('layouts.storefront')

@section('title', 'Your Orders — Rasova')

@section('extra-styles')
.orders-header { padding: 28px 0 20px; }
.orders-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }
.orders-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 16px; }
.orders-empty { padding: 48px 0; text-align: center; }
.orders-empty p { opacity: 0.7; margin-bottom: 18px; }

.order-card { padding: 18px; border-radius: 16px; background: var(--color-surface); }
.order-card-head { display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid var(--color-divider); }
.order-card-head h3 { font-size: 15px; margin: 0; }
.order-card-head .order-date { font-size: 12px; opacity: 0.6; }
.order-status-pill { font-size: 12px; padding: 3px 10px; border-radius: 20px; background: var(--color-accent-2-100); text-transform: capitalize; }
.order-status-pill.delivered { background: color-mix(in srgb, green 15%, transparent); color: green; }
.order-status-pill.failed, .order-status-pill.cancelled { background: color-mix(in srgb, var(--color-error, #b3132d) 12%, transparent); color: var(--color-error, #b3132d); }

.payment-status-pill { font-size: 11px; padding: 2px 9px; border-radius: 20px; text-transform: capitalize; display: inline-flex; align-items: center; gap: 5px; }
.payment-status-pill.paid { background: color-mix(in srgb, green 12%, transparent); color: green; }
.payment-status-pill.pending { background: color-mix(in srgb, #b8860b 12%, transparent); color: #8a6d00; }
.payment-status-pill.failed { background: color-mix(in srgb, var(--color-error, #b3132d) 12%, transparent); color: var(--color-error, #b3132d); }
.payment-status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

.order-failed-note { font-size: 13px; color: var(--color-error, #b3132d); margin: 0 0 14px; }

.order-item-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 8px 0; flex-wrap: wrap; }
.order-item-info { font-size: 14px; }
.order-item-info span { display: block; font-size: 12px; opacity: 0.6; }
.order-review-btn { min-height: 36px; padding: 0 16px; font-size: 12px; }

.order-total-row { display: flex; justify-content: space-between; font-weight: 700; font-size: 14px; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--color-divider); }
@endsection

@section('content')

<header class="wrap orders-header">
    <h1>Your Orders</h1>
</header>

<div class="wrap orders-layout">
    @forelse ($orders as $order)
        <div class="order-card">
            <div class="order-card-head">
                <div>
                    <h3>Order #{{ $order->order_number }}</h3>
                    <span class="order-date">{{ $order->created_at->format('M j, Y') }}</span>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <span class="payment-status-pill {{ $order->payment_status }}">
                        <span class="payment-status-dot"></span>
                        {{ ucfirst($order->payment_status) }}
                    </span>
                    @if ($order->order_status !== $order->payment_status)
                        <span class="order-status-pill {{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
                    @endif
                </div>
            </div>

            @if ($order->payment_status === 'failed')
                <p class="order-failed-note">Payment was unsuccessful. Please try again.</p>
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="margin-bottom:14px;display:inline-block;">Place a new order</a>
            @elseif ($order->payment_status === 'pending')
                <a href="{{ route('checkout.payment', $order->id) }}" class="btn btn-primary" style="margin-bottom:14px;display:inline-block;">Pay Now</a>
            @endif

            @foreach ($order->items as $item)
                <div class="order-item-row">
                    <div class="order-item-info">
                        {{ $item->product_name }}
                        <span>{{ $item->weight }} &times;{{ $item->quantity }}</span>
                    </div>

                    @if ($order->order_status === 'delivered' && $item->product)
                        <a href="{{ route('products.show', $item->product->slug) }}#reviews" class="btn btn-secondary order-review-btn">
                            {{ isset($reviewedProductIds[$item->product_id]) ? 'Edit review' : 'Write a Review' }}
                        </a>
                    @endif
                </div>
            @endforeach

            <div class="order-total-row">
                <span>Total</span>
                <span>&#8377;{{ number_format($order->total_minor / 100, 2) }}</span>
            </div>
        </div>
    @empty
        <div class="orders-empty">
            <p>You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Shop pickles</a>
        </div>
    @endforelse

    @if ($orders->hasPages())
        <div style="display:flex;justify-content:center;gap:6px;margin-top:12px;">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection