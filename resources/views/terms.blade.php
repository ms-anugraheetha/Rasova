@extends('layouts.storefront')

@section('title', 'Terms & Conditions — Rasova')

@section('content')
<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <h1 style="font-size: 32px; margin-bottom: 8px; color: var(--color-accent-700, #b3132d);">Terms &amp; Conditions</h1>
    <p style="color: #888; margin-bottom: 32px;">Last updated: {{-- TODO: date --}}</p>

    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        These terms govern your use of the Rasova website and any orders placed
        through it. By placing an order, you agree to these terms.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Orders &amp; Made-to-Order Products</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO — reflects that Rasova is made-to-order with no fixed stock --}}
        Products are freshly prepared to order rather than shipped from held stock.
        State any expected preparation time and what happens if an item can't be
        fulfilled.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Pricing &amp; Payment</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        Prices are listed in INR and are subject to change without notice. Payment
        is processed securely via Razorpay at checkout.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Shipping &amp; Returns</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        See our <a href="{{ route('shipping') }}">Shipping &amp; Delivery</a> and
        <a href="{{ route('returns') }}">Returns &amp; Refunds</a> pages for details.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Account Responsibility</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        You're responsible for keeping your account credentials confidential and
        for the accuracy of the delivery details you provide.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Limitation of Liability</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO — consult a lawyer for wording appropriate to your jurisdiction --}}
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Governing Law</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        State the jurisdiction whose laws govern these terms (e.g., the courts of
        Kerala, India).
    </p>

    <p style="margin-top: 32px;">
        Questions about these terms? <a href="{{ route('contact') }}">Contact us</a>.
    </p>
</div>
@endsection