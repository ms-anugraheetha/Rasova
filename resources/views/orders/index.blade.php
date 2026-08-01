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
                <span class="order-status-pill {{ $order->order_status === 'delivered' ? 'delivered' : '' }}">{{ ucfirst($order->order_status) }}</span>
            </div>

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