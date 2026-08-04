@extends('layouts.auth')

@section('title', 'Email Verified — Rasova')

@section('extra-styles')
.auth-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 420px; text-align: center; }
.auth-card h1 { font-size: clamp(24px, 6vw, 30px); margin: 0 0 14px; }
.auth-card p { font-size: 15px; opacity: 0.75; line-height: 1.6; margin: 0 0 28px; }
.verified-icon {
    width: 60px; height: 60px; border-radius: 50%; background: color-mix(in srgb, green 12%, transparent);
    color: green; display: grid; place-items: center; margin: 0 auto 20px;
}
.verified-actions .btn { width: 100%; min-height: 48px; }

@media (min-width: 768px) {
    .auth-card { padding: 32px; border-radius: 20px; background: var(--color-surface); }
}
@endsection

@section('content')

<div class="wrap auth-wrap">
    <div class="auth-card">
        <div class="verified-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
        </div>

        <h1>Email Verified</h1>
        <p>Your Rasova account is now active and ready to use.</p>

        <div class="verified-actions">
            <a href="{{ route('login') }}" class="btn btn-primary">Continue to Login</a>
        </div>
    </div>
</div>

@endsection