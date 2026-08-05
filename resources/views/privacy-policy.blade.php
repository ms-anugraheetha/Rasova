@extends('layouts.storefront')

@section('title', 'Privacy Policy — Rasova')

@section('content')
<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <h1 style="font-size: 32px; margin-bottom: 8px; color: var(--color-accent-700, #b3132d);">Privacy Policy</h1>
    <p style="color: #888; margin-bottom: 32px;">Last updated: 05 August 2026</p>

    <p style="margin-bottom: 16px; line-height: 1.6;">
        At Rasova, we respects your privacy. This policy explains what
        personal information we collect when you visit our website, how we
        use it, who we share it with, and the choices you have.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Information We Collect</h2>
    <p style="margin-bottom: 8px; line-height: 1.6;">We collect the following information:</p>
    <ul style="margin: 0 0 16px 20px; line-height: 1.7;">
        <li><strong>Account information:</strong> name, email address,encrypted password, email verification status and account creation date.</li>
        <li><strong>Delivery information:</strong> shipping addresses, phone number, and district/city, used to fulfil your orders.</li>
        <li><strong>Order information:</strong> items ordered, order value, and order history.</li>
        <li><strong>Contact form submissions:</strong> name, email, and message content when you reach out to us.</li>
        <li><strong>Reviews:</strong> any product reviews you submit, along with your display name.</li>
    </ul>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        You can also check out as a guest without creating an account; in that case
        we retain only the order and delivery details needed to fulfil that order.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Payment Information</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        Payments are processed securely by <strong>Razorpay</strong>. Rasova does not
        collect or store your card, UPI, or bank account details on our servers -
        these are handled entirely by Razorpay under its own
        <a href="https://razorpay.com/privacy/" target="_blank" rel="noopener">privacy policy</a>.
        We only receive confirmation of whether a payment succeeded and a payment
        reference ID.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">How We Use Your Information</h2>
    <p style="margin-bottom: 8px; line-height: 1.6;">We use your information to:</p>
    <ul style="margin: 0 0 16px 20px; line-height: 1.7;">
        <li>Process, prepare, and deliver your order</li>
        <li>Send order confirmations, delivery updates, and account-related emails (e.g. email verification, password reset)</li>
        <li>Respond to enquiries submitted through our contact form</li>
        <li>Maintain your account and order history</li>
        <li>Improve the Site and our products</li>
    </ul>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We do not sell your personal information to third parties, and we do not
        use your data for advertising or marketing purposes beyond order-related
        communication.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Who We Share Information With</h2>
    <ul style="margin: 0 0 16px 20px; line-height: 1.7;">
        <li><strong>Razorpay</strong> - to process payments</li>
        <li><strong>Our delivery/courier partner(s)</strong> - to deliver your order, we share your name, address, and phone number {{-- TODO: name the courier(s) once finalized --}}</li>
        <li><strong>Google Workspace / Gmail</strong> - used to send transactional emails (order confirmations, account emails, invoices)</li>
    </ul>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We do not share your data with any other third party except where required
        by law.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Cookies</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We use essential cookies only - to keep you logged in, remember your cart,
        and protect the Site against cross-site request forgery. We do not currently
        use advertising or third-party tracking/analytics cookies.
        {{-- TODO: update this section if analytics (e.g. Google Analytics) is added later --}}
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Data Retention</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We retain your account and order information for as long as your account is
        active, or as needed to comply with our legal and tax obligations, resolve
        disputes, and enforce our agreements.
        {{-- TODO: confirm a specific retention period if you have one, e.g. for tax records --}}
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Your Rights</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        You can access, update, or delete your account information at any time by
        logging into your account, or by contacting us. You may also request a copy
        of the personal information we hold about you, or ask us to delete it,
        subject to our legal obligations (for example, order records we're required
        to retain for tax purposes).
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Data Security</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We take reasonable technical and organizational measures to protect your
        personal information, including encrypted password storage and secure
        (HTTPS) connections. However, no method of transmission over the internet
        is 100% secure, and we cannot guarantee absolute security.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Children's Privacy</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        The Site is not directed at children, and we do not knowingly collect
        personal information from anyone under 18.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Changes to This Policy</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        We may update this policy from time to time. Material changes will be
        reflected by an updated "Last updated" date at the top of this page.
    </p>

    <h2 style="font-size: 22px; margin: 32px 0 12px;">Contact Us</h2>
    <p style="margin-bottom: 16px; line-height: 1.6;">
        If you have questions about this policy or how your data is handled, please
        <a href="{{ route('contact') }}">contact us</a>{{-- TODO: consider adding a
        direct grievance/privacy email address here, which may be required under
        India's DPDP Act --}}.
    </p>
</div>
@endsection