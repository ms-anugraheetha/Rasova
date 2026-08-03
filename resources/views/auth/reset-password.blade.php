@extends('layouts.auth')

@section('title', 'Reset Password — Rasova')

@section('extra-styles')
.auth-wrap { flex: 1; width: 100%; padding: 24px 0 48px; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 420px; }
.auth-card h1 { font-size: clamp(24px, 6vw, 30px); margin: 0 0 6px; text-align: center; }
.auth-card .subtitle { font-size: 14px; opacity: 0.65; text-align: center; margin: 0 0 28px; }

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

.password-strength-row { display: flex; gap: 4px; margin-top: 8px; }
.password-strength-bar { height: 4px; border-radius: 2px; flex: 1; background: var(--color-divider); transition: background 0.2s ease; }
.password-strength-label { font-size: 12px; margin-top: 6px; opacity: 0.7; }
.password-strength-label.weak { color: var(--color-error, #b3132d); }
.password-strength-label.fair { color: #b8860b; }
.password-strength-label.good { color: #2e7d32; }
.password-strength-label.strong { color: var(--color-success, green); font-weight: 600; }

.password-checklist { list-style: none; margin: 10px 0 0; padding: 0; display: flex; flex-direction: column; gap: 5px; }
.password-checklist li { font-size: 12.5px; opacity: 0.5; display: flex; align-items: center; gap: 7px; transition: opacity 0.15s ease, color 0.15s ease; }
.password-checklist li.met { opacity: 1; color: var(--color-success, green); }
.password-checklist li .check-icon { width: 14px; height: 14px; flex-shrink: 0; }

.auth-actions { margin-top: 8px; }
.auth-actions .btn { width: 100%; min-height: 48px; }

@media (min-width: 768px) {
    .auth-card { padding: 32px; border-radius: 20px; background: var(--color-surface); }
}
@endsection

@section('content')

<div class="wrap auth-wrap">
    <div class="auth-card">
        <h1>Reset your password</h1>
        <p class="subtitle">Choose a new password for your Rasova account</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="field-group">
                <label for="email" class="field-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password" class="field-label">New password</label>
                <div class="password-field-wrap">
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    <button type="button" class="password-toggle-btn" data-toggle-for="password" aria-label="Show password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>

                <div class="password-strength-row" id="strengthBar">
                    <div class="password-strength-bar"></div>
                    <div class="password-strength-bar"></div>
                    <div class="password-strength-bar"></div>
                    <div class="password-strength-bar"></div>
                </div>
                <p class="password-strength-label" id="strengthLabel"></p>

                <ul class="password-checklist" id="passwordChecklist">
                    <li data-rule="length">
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"></path></svg>
                        At least 8 characters
                    </li>
                    <li data-rule="uppercase">
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"></path></svg>
                        One uppercase letter
                    </li>
                    <li data-rule="lowercase">
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"></path></svg>
                        One lowercase letter
                    </li>
                    <li data-rule="number">
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"></path></svg>
                        One number
                    </li>
                    <li data-rule="symbol">
                        <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"></path></svg>
                        One special character
                    </li>
                </ul>

                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password_confirmation" class="field-label">Confirm new password</label>
                <div class="password-field-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="password-toggle-btn" data-toggle-for="password_confirmation" aria-label="Show password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
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

    // Block pasting into Confirm Password — forces the user to actually
    // re-type it, so a mismatched-but-pasted-twice typo can't slip through.
    var confirmInput = document.getElementById('password_confirmation');
    if (confirmInput) {
        confirmInput.addEventListener('paste', function (e) {
            e.preventDefault();
        });
    }

    (function () {
        var passwordInput = document.getElementById('password');
        var checklist = document.getElementById('passwordChecklist');
        var strengthBars = document.querySelectorAll('#strengthBar .password-strength-bar');
        var strengthLabel = document.getElementById('strengthLabel');

        var rules = {
            length: function (v) { return v.length >= 8; },
            uppercase: function (v) { return /[A-Z]/.test(v); },
            lowercase: function (v) { return /[a-z]/.test(v); },
            number: function (v) { return /[0-9]/.test(v); },
            symbol: function (v) { return /[^A-Za-z0-9]/.test(v); },
        };

        passwordInput.addEventListener('input', function () {
            var value = passwordInput.value;
            var metCount = 0;

            Object.keys(rules).forEach(function (ruleName) {
                var met = rules[ruleName](value);
                if (met) metCount++;
                var li = checklist.querySelector('[data-rule="' + ruleName + '"]');
                li.classList.toggle('met', met);
            });

            var strengthLevel = value.length === 0 ? 0 : Math.max(1, metCount - 1);
            var labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
            var colors = ['', '#b3132d', '#b8860b', '#2e7d32', 'green'];

            strengthBars.forEach(function (bar, i) {
                bar.style.background = (i < strengthLevel) ? colors[strengthLevel] : '';
            });

            strengthLabel.textContent = labels[strengthLevel];
            strengthLabel.className = 'password-strength-label ' + (labels[strengthLevel] || '').toLowerCase();
        });
    })();
</script>
@endpush