@extends('layouts.auth')

@section('title', 'Verify Your Email — Rasova')

@section('extra-styles')
.auth-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 440px; text-align: center; }
.auth-card h1 { font-size: clamp(24px, 6vw, 30px); margin: 0 0 14px; }
.auth-card p { font-size: 15px; opacity: 0.75; line-height: 1.6; margin: 0 0 24px; }
.verify-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--color-accent-2-100); color: var(--color-accent-700); display: grid; place-items: center; margin: 0 auto 20px; }
.auth-status { font-size: 13px; color: var(--color-success, green); margin-bottom: 20px; }
.auth-status.cooldown { color: var(--color-text); opacity: 0.65; }
.verify-actions { display: flex; flex-direction: column; gap: 12px; }
.verify-actions .btn { width: 100%; min-height: 48px; }
.verify-actions .btn:disabled { opacity: 0.5; cursor: not-allowed; }
.verify-logout { text-align: center; font-size: 13px; margin-top: 20px; opacity: 0.6; }

@media (min-width: 768px) {
    .auth-card { padding: 32px; border-radius: 20px; background: var(--color-surface); }
}
@endsection

@section('content')

<div class="wrap auth-wrap">
    <div class="auth-card">
        <div class="verify-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
        </div>

        <h1>Verify your email</h1>
        <p>
            Your account has been created successfully.<br>
            We've sent a verification link to your email address.<br>
            Please verify your email before logging in.
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="auth-status">A new verification link has been sent to your email address.</p>
        @elseif (session('status') === 'verification-link-cooldown')
            <p class="auth-status cooldown">Please wait a moment before requesting another link.</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="verify-actions" id="resendForm">
            @csrf
            <button type="submit" class="btn btn-primary" id="resendBtn">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="verify-logout">
            @csrf
            <button type="submit" style="background:none;border:none;color:inherit;text-decoration:underline;cursor:pointer;font-family:inherit;font-size:13px;">Log out</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        var btn = document.getElementById('resendBtn');
        var cooldownSeconds = 60;
        var justSent = {{ session('status') ? 'true' : 'false' }};

        function startCooldown() {
            var remaining = cooldownSeconds;
            btn.disabled = true;
            var originalText = 'Resend Verification Email';

            var timer = setInterval(function () {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    btn.textContent = originalText;
                } else {
                    btn.textContent = 'Resend available in ' + remaining + 's';
                }
            }, 1000);
        }

        if (justSent) {
            startCooldown();
        }
    })();
</script>
@endpush