@extends('layouts.storefront')

@section('title', 'Your Account — Rasova')

@section('extra-styles')
.profile-header { padding: 28px 0 20px; }
.profile-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }

.profile-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 20px; }
.profile-card {
    padding: 20px; border-radius: 18px; background: var(--color-surface);
}
.profile-card header { margin-bottom: 18px; }
.profile-card h2 { font-size: 17px; margin: 0 0 4px; }
.profile-card header p { font-size: 13px; opacity: 0.65; margin: 0; }

.field-group { margin-bottom: 16px; }
.field-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
.field-group input {
    width: 100%; min-height: 46px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 15px;
}
.field-error { color: var(--color-error, #b3132d); font-size: 12px; margin-top: 6px; }
.field-status { font-size: 13px; opacity: 0.7; margin-top: 4px; }

.card-actions { display: flex; align-items: center; gap: 14px; margin-top: 4px; }
.card-actions .btn { min-height: 44px; padding: 0 22px; }
.saved-note { font-size: 13px; opacity: 0.65; }

.danger-card { border: 1px solid color-mix(in srgb, var(--color-error, #b3132d) 30%, transparent); }
.danger-card p { font-size: 13px; opacity: 0.7; margin: 0 0 16px; }

.delete-scrim { display: none; }
.delete-scrim.show { display: block; position: fixed; inset: 0; background: color-mix(in srgb, #000 45%, transparent); z-index: 90; }
.delete-modal {
    display: none;
    position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%);
    width: min(420px, 90vw); background: var(--color-bg); border-radius: 18px;
    padding: 24px; box-shadow: var(--shadow-lg); z-index: 95;
}
.delete-modal.show { display: block; }
.delete-modal h3 { font-size: 17px; margin: 0 0 8px; }
.delete-modal p { font-size: 13px; opacity: 0.7; margin: 0 0 16px; }
.delete-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }

@media (min-width: 768px) {
    .profile-card { padding: 28px; }
}
@endsection

@section('content')

<header class="wrap profile-header">
    <h1>Your account</h1>
</header>

<div class="wrap profile-layout">

    {{-- Profile information --}}
    <section class="profile-card">
        <header>
            <h2>Profile information</h2>
            <p>Update your account's name and email address.</p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="field-group">
                <label for="first_name" class="field-label">First name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="given-name">
                @error('first_name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="last_name" class="field-label">Last name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name">
                @error('last_name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="email" class="field-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="field-status">
                        Your email address is unverified.
                        <button form="send-verification" style="border:none;background:none;color:var(--color-accent-700);text-decoration:underline;cursor:pointer;padding:0;font-size:13px;">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="field-status" style="color:var(--color-success, green);">A new verification link has been sent to your email address.</p>
                    @endif
                @endif
            </div>

            <div class="card-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                @if (session('status') === 'profile-updated')
                    <span class="saved-note">Saved.</span>
                @endif
            </div>
        </form>
    </section>

    {{-- Password --}}
    <section class="profile-card">
        <header>
            <h2>Update password</h2>
            <p>Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="field-group">
                <label for="update_password_current_password" class="field-label">Current password</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="update_password_password" class="field-label">New password</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="update_password_password_confirmation" class="field-label">Confirm password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="card-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                @if (session('status') === 'password-updated')
                    <span class="saved-note">Saved.</span>
                @endif
            </div>
        </form>
    </section>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-secondary" style="width:100%;">Log out</button>
    </form>

    {{-- Delete account --}}
    <section class="profile-card danger-card">
        <header>
            <h2>Delete account</h2>
        </header>
        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data you wish to retain before proceeding.</p>

        <button type="button" class="btn btn-secondary" style="color:var(--color-error, #b3132d);border-color:var(--color-error, #b3132d);" id="openDeleteModalBtn">
            Delete account
        </button>
    </section>
</div>

<div class="delete-scrim" id="deleteScrim"></div>
<div class="delete-modal" id="deleteModal">
    <h3>Are you sure you want to delete your account?</h3>
    <p>This is permanent. Enter your password to confirm.</p>

    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="field-group">
            <label for="delete_password" class="field-label" style="position:absolute;width:1px;height:1px;overflow:hidden;">Password</label>
            <input id="delete_password" name="password" type="password" placeholder="Password">
            @error('password', 'userDeletion')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="delete-modal-actions">
            <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
            <button type="submit" class="btn btn-primary" style="background:var(--color-error, #b3132d);border-color:var(--color-error, #b3132d);">Delete account</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        var openBtn = document.getElementById('openDeleteModalBtn');
        var cancelBtn = document.getElementById('cancelDeleteBtn');
        var modal = document.getElementById('deleteModal');
        var scrim = document.getElementById('deleteScrim');

        function open() { modal.classList.add('show'); scrim.classList.add('show'); }
        function close() { modal.classList.remove('show'); scrim.classList.remove('show'); }

        openBtn && openBtn.addEventListener('click', open);
        cancelBtn && cancelBtn.addEventListener('click', close);
        scrim && scrim.addEventListener('click', close);

        @if ($errors->userDeletion->isNotEmpty())
            open();
        @endif
    })();
</script>
@endpush