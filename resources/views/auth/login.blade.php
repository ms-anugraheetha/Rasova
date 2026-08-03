@extends('layouts.auth')

@section('title', 'Log In — Rasova')

@section('extra-styles')
.auth-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 400px; }
.auth-card h1 { font-size: clamp(24px, 6vw, 30px); margin: 0 0 6px; text-align: center; }
.auth-card .subtitle { font-size: 14px; opacity: 0.65; text-align: center; margin: 0 0 28px; }

.auth-status { font-size: 13px; color: var(--color-success, green); margin-bottom: 16px; text-align: center; }

.field-group { margin-bottom: 16px; }
.field-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
.field-group input {
    width: 100%; min-height: 46px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 15px;
}
.field-error { color: var(--color-error, #b3132d); font-size: 12px; margin-top: 6px; }
.password-field-wrap { position: relative; }
.password-field-wrap input { padding-right: 44px; }
.password-toggle-btn {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: var(--color-text); opacity: 0.5;
    display: grid; place-items: center; padding: 4px;
}
.password-toggle-btn:hover { opacity: 0.8; }

.remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.remember-row input { width: 18px; height: 18px; }
.remember-row label { font-size: 14px; }

.auth-actions { display: flex; flex-direction: column; gap: 12px; }
.auth-actions .btn { width: 100%; min-height: 48px; }
.auth-forgot { font-size: 13px; text-align: center; }

.auth-footer { text-align: center; font-size: 14px; margin-top: 24px; opacity: 0.75; }

.auth-back-link {
    display: inline-flex; align-items: center; gap: 6px; font-size: 13px; opacity: 0.65;
    margin-bottom: 16px; text-decoration: none; color: inherit;
}
.auth-back-link:hover { opacity: 1; }

@media (min-width: 768px) {
    .auth-card { padding: 32px; border-radius: 20px; background: var(--color-surface); }
}
@endsection

@section('content')

<div class="wrap auth-wrap">
    <div class="auth-card">
        <a href="{{ route('gateway') }}" class="auth-back-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"></path></svg>
            Back
        </a>

        <h1>Welcome back</h1>
        <p class="subtitle">
            @if (request('intent') === 'wishlist')
                Log in to save items to your wishlist
            @else
                Log in to your Rasova account
            @endif
        </p>

        @if (session('status'))
            <p class="auth-status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field-group">
                <label for="email" class="field-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Password</label>
                <div class="password-field-wrap">
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    <button type="button" class="password-toggle-btn" data-toggle-for="password" aria-label="Show password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-row">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Log in</button>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-forgot">Forgot your password?</a>
                @endif
            </div>
        </form>

        @if (Route::has('register'))
            <p class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-toggle-for'));
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });
</script>
@endpush