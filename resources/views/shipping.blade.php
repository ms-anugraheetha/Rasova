@extends('layouts.storefront')

@section('title', 'Shipping & Delivery — Rasova')

@section('content')
<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <h1 style="font-size: 32px; margin-bottom: 24px; color: var(--color-accent-700, #b3132d);">Shipping & Delivery</h1>

    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO: Replace with real shipping policy content --}}
        This page is a placeholder. Add details on shipping timeframes, regions served,
        courier partners, and any charges here.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Processing Time</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        Since Rasova pickles are made to order, add your typical preparation window here
        before an order ships.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Delivery Areas &amp; Timeframes</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        {{-- TODO --}}
        List the districts/states you ship to and expected delivery windows for each.
    </p>

    <p style="margin-top: 32px;">
        Questions about your order? <a href="{{ route('contact') }}">Contact us</a>.
    </p>
</div>
@endsection