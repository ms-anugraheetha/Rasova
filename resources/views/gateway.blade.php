@extends('layouts.auth')

@section('title', 'Welcome — Rasova')

@section('extra-styles')
.gateway-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.gateway-card { width: 100%; max-width: 380px; text-align: center; }
.gateway-logo { width: 64px; height: 53px; border-radius: 50%; margin-bottom: 20px; }
.gateway-card h1 { font-size: clamp(26px, 7vw, 34px); margin: 0 0 12px; }
.gateway-card p { font-size: 15px; opacity: 0.72; line-height: 1.6; margin: 0 0 32px; }
.gateway-actions { display: flex; flex-direction: column; gap: 12px; }
.gateway-actions .btn { width: 100%; min-height: 48px; font-size: 15px; }
.gateway-guest-btn {
    background: none; border: none; color: var(--color-accent-700); font-size: 14px;
    cursor: pointer; padding: 8px; font-family: inherit; text-decoration: underline;
}
@endsection

@section('content')

<div class="wrap gateway-wrap">
    <div class="gateway-card">
        <img src="{{ asset('design/assets/rasova-logo.png') }}" alt="Rasova" class="gateway-logo">
        <h1>Welcome to Rasova</h1>
        <p>Experience authentic homemade Kerala pickles crafted with tradition and delivered fresh.</p>

        <div class="gateway-actions">
            <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Create Account</a>

            <form method="POST" action="{{ route('gateway.guest') }}">
                @csrf
                <button type="submit" class="gateway-guest-btn">Continue as Guest</button>
            </form>
        </div>
    </div>
</div>

@endsection