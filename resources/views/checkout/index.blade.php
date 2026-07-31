@extends('layouts.storefront')

@section('title', 'Checkout — Rasova')

@section('extra-styles')
.checkout-header { padding: 28px 0 20px; }
.checkout-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }
.checkout-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 32px; }

.checkout-field { margin-bottom: 14px; }
.checkout-field input {
    width: 100%; min-height: 46px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 15px;
}
.checkout-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.checkout-submit { width: 100%; min-height: 48px; margin-top: 6px; }

.order-summary { padding: 20px; border-radius: 16px; background: var(--color-surface); }
.order-summary h2 { font-size: 16px; margin: 0 0 16px; }
.order-line { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; }
.order-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 16px; border-top: 1px solid var(--color-divider); padding-top: 14px; margin-top: 8px; }

@media (min-width: 1024px) {
    .checkout-layout { flex-direction: row-reverse; gap: 48px; }
    .checkout-form { flex: 1.3; }
    .order-summary { flex: 1; align-self: flex-start; position: sticky; top: 90px; }
}
@endsection

@section('content')

<header class="wrap checkout-header">
    <h1>Checkout</h1>
</header>

@if (session('error'))
    <div class="wrap" style="padding-bottom:16px;">
        <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="wrap" style="padding-bottom:16px;">
        @foreach ($errors->all() as $error)
            <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="wrap checkout-layout">
    <form method="POST" action="{{ route('checkout.store') }}" class="checkout-form">
        @csrf
        <h2 style="font-size:16px;margin:0 0 16px;">Shipping details</h2>

        @guest
            <div class="checkout-field">
                <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
            </div>
        @endguest

        <div class="checkout-field">
            <input type="text" name="full_name" placeholder="Full name" value="{{ old('full_name') }}" required>
        </div>

        <div class="checkout-field">
            <input type="text" name="phone" placeholder="Phone number" value="{{ old('phone') }}" required>
        </div>

        <div class="checkout-field">
            <input type="text" name="address_line_1" placeholder="Address line 1" value="{{ old('address_line_1') }}" required>
        </div>

        <div class="checkout-field">
            <input type="text" name="address_line_2" placeholder="Address line 2 (optional)" value="{{ old('address_line_2') }}">
        </div>

        <div class="checkout-field">
            <input type="text" name="landmark" placeholder="Landmark (optional)" value="{{ old('landmark') }}">
        </div>

        <div class="checkout-field checkout-row">
            <input type="text" name="city" placeholder="City" value="{{ old('city') }}" required>
            <input type="text" name="state" placeholder="State" value="{{ old('state') }}" required>
        </div>

        <div class="checkout-field">
            <input type="text" name="postal_code" placeholder="Postal code" value="{{ old('postal_code') }}" required>
        </div>

        <button type="submit" class="btn btn-primary checkout-submit">Place order</button>

        @guest
            <p style="font-size:13px;opacity:0.65;text-align:center;margin-top:14px;">
                Have an account? <a href="{{ route('login') }}">Log in</a> to check out faster.
            </p>
        @endguest
    </form>

    <div class="order-summary">
        <h2>Order summary</h2>
        @foreach ($items as $item)
            <div class="order-line">
                <span>{{ $item->productVariant->product->name }} ({{ $item->productVariant->weight }}) &times;{{ $item->quantity }}</span>
                <span>&#8377;{{ number_format($item->productVariant->price_minor * $item->quantity / 100, 2) }}</span>
            </div>
        @endforeach
        <div class="order-total">
            <span>Subtotal</span>
            <span>&#8377;{{ number_format($subtotal / 100, 2) }}</span>
        </div>
    </div>
</div>

@endsection