@extends('layouts.auth')

@section('title', 'Create Account — Rasova')

@section('extra-styles')
.auth-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 420px; }
.auth-card h1 { font-size: clamp(24px, 6vw, 30px); margin: 0 0 6px; text-align: center; }
.auth-card .subtitle { font-size: 14px; opacity: 0.65; text-align: center; margin: 0 0 28px; }

.field-row { display: flex; gap: 12px; }
.field-row .field-group { flex: 1; }

.field-group { margin-bottom: 16px; }
.field-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
.field-group input {
    width: 100%; min-height: 46px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 15px;
}
.field-error { color: var(--color-error, #b3132d); font-size: 12px; margin-top: 6px; }

.auth-actions { margin-top: 8px; }
.auth-actions .btn { width: 100%; min-height: 48px; }

.auth-footer { text-align: center; font-size: 14px; margin-top: 24px; opacity: 0.75; }

@media (min-width: 768px) {
    .auth-card { padding: 32px; border-radius: 20px; background: var(--color-surface); }
}
@media (max-width: 420px) {
    .field-row { flex-direction: column; gap: 0; }
}
@endsection

@section('content')

<div class="wrap auth-wrap">
    <div class="auth-card">
        <h1>Create your account</h1>
    

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field-row">
                <div class="field-group">
                    <label for="first_name" class="field-label">First name</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name">
                    @error('first_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field-group">
                    <label for="last_name" class="field-label">Last name</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
                    @error('last_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field-group">
                <label for="email" class="field-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password_confirmation" class="field-label">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>

        <p class="auth-footer">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </div>
</div>

@endsection