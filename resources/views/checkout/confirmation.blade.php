@extends('layouts.storefront')

@section('title', 'Order Confirmed — Rasova')

@section('extra-styles')
.confirm-wrap { padding: 56px 0; text-align: center; }
.confirm-wrap h1 { font-size: clamp(22px, 6vw, 30px); margin: 0 0 10px; }
.confirm-wrap > p { opacity: 0.7; margin: 0 0 28px; max-width: 48ch; margin-left: auto; margin-right: auto; }
.confirm-summary { text-align: left; padding: 20px; border-radius: 16px; background: var(--color-surface); max-width: 480px; margin: 0 auto; }
.confirm-line { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; }
.confirm-total { display: flex; justify-content: space-between; font-weight: 700; border-top: 1px solid var(--color-divider); padding-top: 14px; margin-top: 8px; }
.confirm-note { font-size: 13px; opacity: 0.6; margin-top: 24px; }
.confirm-guest-cta { margin-top: 32px; padding: 24px; border-radius: 16px; background: var(--color-accent-2-100); }
.confirm-guest-cta p { font-size: 14px; opacity: 0.85; margin: 0 0 16px; }
.confirm-guest-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.confirm-guest-actions .btn { min-height: 44px; padding: 0 22px; }
@endsection

@section('content')

<div class="wrap confirm-wrap">
    <h1>Thank you for your order!</h1>
    <p>Order #{{ $order->order_number }} has been placed and is awaiting payment confirmation.</p>

    <div class="confirm-summary">
        @foreach ($order->items as $item)
            <div class="confirm-line">
                <span>{{ $item->product_name }} ({{ $item->weight }}) &times;{{ $item->quantity }}</span>
                <span>&#8377;{{ number_format($item->total_price_minor / 100, 2) }}</span>
            </div>
        @endforeach
        <div class="confirm-total">
            <span>Total</span>
            <span>&#8377;{{ number_format($order->total_minor / 100, 2) }}</span>
        </div>
    </div>

    <p class="confirm-note">Note: Payment gateway integration is still in development, so this order is currently marked "pending" and won't auto-confirm yet.</p>

    @guest
        <div class="confirm-guest-cta">
            <p>Create an account to easily track future orders and save your information.</p>
            <div class="confirm-guest-actions">
                <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
    @endguest
</div>

@endsection