@extends('layouts.storefront')

@section('title', 'Contact — Rasova')

@section('extra-styles')
.contact-hero { padding: 40px 0 24px; }
.contact-hero h1 { font-size: clamp(28px, 7vw, 44px); margin: 0 0 10px; }
.contact-hero p { font-size: 15px; opacity: 0.75; max-width: 52ch; margin: 0; }

.contact-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 40px; }

.contact-info { display: flex; flex-direction: column; gap: 28px; }
.contact-info-item { display: flex; align-items: flex-start; gap: 14px; }
.contact-info-icon {
    width: 40px; height: 40px; border-radius: 50%; background: var(--color-accent-2-100);
    display: grid; place-items: center; flex-shrink: 0; color: var(--color-accent-700);
}
.contact-info h3 { font-size: 13px; margin: 0 0 4px; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.04em; }
.contact-info p { margin: 0; font-size: 15px; }

.contact-form { display: flex; flex-direction: column; gap: 18px; width: 100%; }
.contact-field { display: flex; flex-direction: column; gap: 6px; }
.contact-field label { font-size: 13px; font-weight: 600; }
.contact-field input, .contact-field textarea {
    width: 100%; min-height: 54px; padding: 0 16px; border-radius: 12px;
    border: 1.5px solid var(--color-divider); background: var(--color-bg); color: inherit;
    font-size: 15px; font-family: inherit; box-sizing: border-box;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.contact-field input:focus, .contact-field textarea:focus {
    outline: none; border-color: var(--color-accent); box-shadow: 0 0 0 3px var(--color-accent-2-100);
}
.contact-field input.field-error, .contact-field textarea.field-error {
    border-color: var(--color-error, #b3132d);
}
.contact-field textarea {
    min-height: 180px; padding: 14px 16px; resize: none; line-height: 1.6;
}
.field-error-message { font-size: 12px; color: var(--color-error, #b3132d); min-height: 16px; }

.contact-submit-btn {
    width: 100%; min-height: 52px; font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;
}
.contact-submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }
.contact-spinner {
    width: 16px; height: 16px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4);
    border-top-color: #fff; animation: contact-spin 0.7s linear infinite; display: none;
}
.contact-submit-btn.loading .contact-spinner { display: inline-block; }
@keyframes contact-spin { to { transform: rotate(360deg); } }

.contact-success-banner {
    background: color-mix(in srgb, green 10%, transparent); color: green;
    border-radius: 12px; padding: 14px 18px; font-size: 14px; margin-bottom: 24px;
}

@media (min-width: 768px) {
    .contact-layout { flex-direction: row; gap: 72px; align-items: flex-start; }
    .contact-info { flex: 0 0 32%; max-width: 300px; }
    .contact-form-col { flex: 1; display: flex; justify-content: flex-end; }
    .contact-form { max-width: 520px; }
}
@endsection

@section('content')
<header class="wrap contact-hero">
    <h1>Get in touch</h1>
    <p>Questions about an order, a wholesale enquiry, or just want to say hello — we read every message.</p>
</header>

<div class="wrap contact-layout">
    <div class="contact-info">
        <div class="contact-info-item">
            <div class="contact-info-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
            </div>
            <div>
                <h3>Email</h3>
                <p>Rasovadelights@gmail.com</p>
            </div>
        </div>
        <div class="contact-info-item">
            <div class="contact-info-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"></path></svg>
            </div>
            <div>
                <h3>Phone</h3>
                <p>+91 00000 00000</p>
            </div>
        </div>
        <div class="contact-info-item">
            <div class="contact-info-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            </div>
            <div>
                <h3>Based in</h3>
                <p>Kerala, India</p>
            </div>
        </div>
    </div>

    <div class="contact-form-col">
        <form method="POST" action="{{ route('contact.submit') }}" class="contact-form" id="contactForm" novalidate>
            @csrf

            @if (session('status'))
                <div class="contact-success-banner">{{ session('status') }}</div>
            @endif

            <div class="contact-field">
                <label for="contact_name">Full Name</label>
                <input type="text" id="contact_name" name="name" placeholder="Your name" value="{{ old('name') }}" required>
                <span class="field-error-message" data-error-for="name"></span>
            </div>

            <div class="contact-field">
                <label for="contact_email">Email Address</label>
                <input type="email" id="contact_email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                <span class="field-error-message" data-error-for="email"></span>
            </div>

            <div class="contact-field">
                <label for="contact_subject">Subject</label>
                <input type="text" id="contact_subject" name="subject" placeholder="What's this about?" value="{{ old('subject') }}">
                <span class="field-error-message" data-error-for="subject"></span>
            </div>

            <div class="contact-field">
                <label for="contact_message">Message</label>
                <textarea id="contact_message" name="message" placeholder="Tell us how we can help..." required>{{ old('message') }}</textarea>
                <span class="field-error-message" data-error-for="message"></span>
            </div>

            <button type="submit" class="btn btn-primary contact-submit-btn" id="contactSubmitBtn">
                <span class="contact-spinner"></span>
                <span class="contact-submit-label">Send Message</span>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('contactForm');
        var submitBtn = document.getElementById('contactSubmitBtn');
        var submitLabel = submitBtn.querySelector('.contact-submit-label');

        var fields = {
            name: document.getElementById('contact_name'),
            email: document.getElementById('contact_email'),
            message: document.getElementById('contact_message'),
        };

        function setError(fieldName, message) {
            var input = fields[fieldName];
            var errorEl = form.querySelector('[data-error-for="' + fieldName + '"]');
            if (message) {
                input.classList.add('field-error');
                errorEl.textContent = message;
            } else {
                input.classList.remove('field-error');
                errorEl.textContent = '';
            }
        }

        function validate() {
            var valid = true;

            if (!fields.name.value.trim()) {
                setError('name', 'Please enter your name.');
                valid = false;
            } else {
                setError('name', '');
            }

            var email = fields.email.value.trim();
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                setError('email', 'Please enter your email address.');
                valid = false;
            } else if (!emailPattern.test(email)) {
                setError('email', 'Please enter a valid email address.');
                valid = false;
            } else {
                setError('email', '');
            }

            var message = fields.message.value.trim();
            if (!message) {
                setError('message', 'Please enter a message.');
                valid = false;
            } else if (message.length < 10) {
                setError('message', 'Please enter at least 10 characters.');
                valid = false;
            } else {
                setError('message', '');
            }

            return valid;
        }

        Object.keys(fields).forEach(function (key) {
            fields[key].addEventListener('blur', validate);
        });

        form.addEventListener('submit', function (e) {
            if (!validate()) {
                e.preventDefault();
                return;
            }

            // Native form submission proceeds from here (full page POST) —
            // the loading state stays visible until the page navigates away.
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitLabel.textContent = 'Sending...';
        });
    })();
</script>
@endpush