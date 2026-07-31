@extends('layouts.storefront')

@section('title', 'Contact — Rasova')

@section('extra-styles')
.contact-hero { padding: 40px 0 24px; }
.contact-hero h1 { font-size: clamp(28px, 7vw, 44px); margin: 0 0 10px; }
.contact-hero p { font-size: 15px; opacity: 0.75; max-width: 52ch; margin: 0; }
.contact-layout { padding-bottom: 56px; display: flex; flex-direction: column; gap: 32px; }
.contact-info { display: flex; flex-direction: column; gap: 18px; }
.contact-info h3 { font-size: 14px; margin: 0 0 4px; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.04em; }
.contact-info p { margin: 0; font-size: 15px; }
.contact-form { display: flex; flex-direction: column; gap: 14px; }
.contact-form input, .contact-form textarea { min-height: 48px; }
.contact-form textarea { min-height: 120px; padding: 12px; }
.contact-form button { min-height: 48px; }

@media (min-width: 768px) {
    .contact-layout { flex-direction: row; gap: 56px; }
    .contact-info { flex: 0 0 240px; }
    .contact-form { flex: 1; max-width: 480px; }
}
@endsection

@section('content')
<header class="wrap contact-hero">
    <h1>Get in touch</h1>
    <p>Questions about an order, a wholesale enquiry, or just want to say hello - we read every message.</p>
</header>

<div class="wrap contact-layout">
    <div class="contact-info">
        <div>
            <h3>Email</h3>
            <p>Rasovadelights@gmail.com</p>
        </div>
        <div>
            <h3>Phone</h3>
            <p>+91 00000 00000</p>
        </div>
        <div>
            <h3>Based in</h3>
            <p>Kerala, India</p>
        </div>
    </div>

    <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
        @csrf
        <input class="input" type="text" name="name" placeholder="Name" required>
        <input class="input" type="email" name="email" placeholder="Email Address" required>
        <textarea class="input" name="message" placeholder="How can we help?" required></textarea>
        <button type="submit" class="btn btn-primary">Send message</button>
    </form>
</div>
@endsection