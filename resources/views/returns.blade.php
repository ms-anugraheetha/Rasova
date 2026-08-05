@extends('layouts.storefront')

@section('title', 'Returns & Refunds — Rasova')

@section('content')
<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <h1 style="font-size: 32px; margin-bottom: 24px; color: var(--color-accent-700, #b3132d);">Returns &amp; Refunds</h1>

    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO: Replace with real returns policy content --}}
        This page is a placeholder. Since pickles are a made-to-order perishable food
        product, your returns policy likely differs from typical retail — describe your
        actual policy here (e.g., damaged-in-transit claims, quality issues, etc.).
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Damaged or Incorrect Orders</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        Describe the process and timeframe for reporting a damaged jar or wrong item.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Refunds</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        Describe how and when refunds are issued (e.g., original payment method,
        processing time).
    </p>

    <p style="margin-top: 32px;">
        Need help with an order? <a href="{{ route('contact') }}">Contact us</a>.
    </p>
</div>
@endsection