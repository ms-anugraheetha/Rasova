<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rasova — Homemade Kerala Pickles')</title>

    <link rel="stylesheet" href="{{ asset('design/_ds/organic/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('design/rasova-theme.css') }}">

    <style>
        /* ==========================================================
           MOBILE-FIRST BASE (default: phones, ~360–639px)
           Everything below is the mobile layout. Tablet/desktop rules
           are added ONLY as min-width overrides further down.
           ========================================================== */
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--color-bg); color: var(--color-text); overflow-x: clip; }
        a { color: var(--color-accent-700); }
        a:hover { color: var(--color-accent-600); }

        /* Fluid container: full-bleed on phones, gains max-width as screen grows */
        .wrap {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Touch targets: 44px minimum on every screen size per accessibility guidance */
        .icon-btn {
            width: 44px; height: 44px; display: grid; place-items: center;
            border-radius: 50%; color: var(--color-text); position: relative;
            cursor: pointer; text-decoration: none;
        }
        .icon-btn:hover { background: color-mix(in srgb, var(--color-text) 7%, transparent); }
        .badge {
            position: absolute; top: 2px; right: 2px; width: 16px; height: 16px;
            border-radius: 50%; background: var(--color-accent); color: var(--color-bg);
            font-size: 9px; display: grid; place-items: center; font-weight: 700;
        }

        /* Top nav: on mobile only logo + icons show; link list lives in the hamburger drawer */
        .site-nav {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; padding: 14px 20px;
            position: sticky; top: 0; z-index: 40;
            background: color-mix(in srgb, var(--color-bg) 92%, transparent);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--color-divider);
        }
        .nav-links { display: none; }
        .hamburger-btn { display: grid; }
        .nav-links.mobile-open {
            display: flex; flex-direction: column; gap: 4px;
            position: fixed; top: 0; right: 0; bottom: 0; width: min(300px, 84vw);
            background: var(--color-bg); border-left: 1px solid var(--color-divider);
            padding: 24px 20px; box-shadow: var(--shadow-lg); z-index: 60;
        }
        .nav-links.mobile-open a { padding: 12px 4px; font-size: 17px; border-bottom: 1px solid var(--color-divider); }
        .nav-scrim { display: none; }
        .nav-scrim.show {
            display: block; position: fixed; inset: 0; background: color-mix(in srgb, #000 40%, transparent);
            z-index: 55;
        }

        /* Bottom tab bar: primary mobile navigation, thumb-reachable */
        .mobile-tabbar {
            display: flex; position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--color-bg); border-top: 1px solid var(--color-divider);
            padding: 6px 4px calc(6px + env(safe-area-inset-bottom));
            justify-content: space-around; z-index: 50;
        }
        .mt-item {
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            font-size: 10px; text-decoration: none; color: var(--color-text); opacity: 0.6;
            min-width: 48px; min-height: 48px; justify-content: center; border-radius: 12px;
        }
        .mt-item.active { opacity: 1; color: var(--color-accent); }
        body { padding-bottom: calc(64px + env(safe-area-inset-bottom)); }

        .footer-grid { display: grid; grid-template-columns: 1fr; gap: 28px; }

        @yield('extra-styles')
    </style>
    @stack('head')
</head>
<body>

<nav class="site-nav">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <img src="{{ asset('design/assets/rasova-logo.png') }}" alt="Rasova" style="width:40px;height:33px;border-radius:50%">
        <span class="nav-brand">Rasova</span>
    </a>

    <div class="nav-links" id="navLinks">
        <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
        <a href="{{ route('products.index') }}" @if(request()->routeIs('products.*')) aria-current="page" @endif>Shop</a>
        <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
        <a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
    </div>

    <div style="display:flex;gap:2px;align-items:center;">
        <div class="icon-btn" title="Search">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
        </div>

        <a href="{{ route('cart.index') }}" class="icon-btn" title="Cart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            @if(($cartCount ?? 0) > 0)
                <span class="badge">{{ $cartCount }}</span>
            @endif
        </a>

        <div class="hamburger-btn icon-btn" title="Menu" id="hamburgerBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16"></path><path d="M4 6h16"></path><path d="M4 18h16"></path></svg>
        </div>
    </div>
</nav>

<div class="nav-scrim" id="navScrim"></div>

<main>
    @yield('content')
</main>

<div class="mobile-tabbar">
    <a href="{{ route('home') }}" class="mt-item @if(request()->routeIs('home')) active @endif">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1Z"></path></svg>Home
    </a>
    <a href="{{ route('products.index') }}" class="mt-item @if(request()->routeIs('products.*')) active @endif">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>Shop
    </a>
    <a href="{{ route('cart.index') }}" class="mt-item @if(request()->routeIs('cart.*')) active @endif">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path></svg>Cart
    </a>
    @auth
        <a href="{{ route('profile.edit') }}" class="mt-item @if(request()->routeIs('profile.*')) active @endif">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Account
        </a>
    @else
        <a href="{{ route('login') }}" class="mt-item @if(request()->routeIs('login')) active @endif">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Account
        </a>
    @endauth
</div>

<footer class="wrap" style="padding:40px 20px calc(88px);">
    <div class="footer-grid" style="padding-bottom:28px;">
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                <img src="{{ asset('design/assets/rasova-logo.png') }}" style="width:32px;height:32px;border-radius:50%;">
                <span class="nav-brand" style="margin:0;">Rasova</span>
            </div>
            <p style="font-size:13px;opacity:0.7;max-width:32ch;">Homemade Kerala pickles, pressed in small batches and shipped nationwide.</p>
        </div>
        <div>
            <h6 style="margin-bottom:12px;">Shop</h6>
            <div style="display:grid;gap:10px;font-size:14px;">
                @foreach(($footerCategories ?? []) as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
        <div>
            <h6 style="margin-bottom:12px;">Company</h6>
            <div style="display:grid;gap:10px;font-size:14px;">
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
        </div>
        <div>
            <h6 style="margin-bottom:12px;">Help</h6>
            <div style="display:grid;gap:10px;font-size:14px;">
                {{-- Track order link removed: no customer-facing orders.index route yet.
                     Add one (e.g. Route::get('/orders', ...)->name('orders.index'))
                     then restore this link. --}}
                <a href="#">Shipping</a>
                <a href="#">Returns</a>
            </div>
        </div>
    </div>
    <div class="hr"></div>
    <div style="display:flex;flex-direction:column;gap:8px;padding-top:20px;font-size:12px;opacity:0.6;">
        <span>&copy; {{ date('Y') }} Rasova. All rights reserved.</span>
        <span>Made in Kerala, shipped across India.</span>
    </div>
</footer>

<script>
    (function () {
        var btn = document.getElementById('hamburgerBtn');
        var links = document.getElementById('navLinks');
        var scrim = document.getElementById('navScrim');
        function close() { links.classList.remove('mobile-open'); scrim.classList.remove('show'); }
        btn && btn.addEventListener('click', function () {
            links.classList.toggle('mobile-open');
            scrim.classList.toggle('show');
        });
        scrim && scrim.addEventListener('click', close);
    })();
</script>

@stack('scripts')

<style>
    /* ==========================================================
       TABLET — min-width 768px
       More breathing room, 2-column grids, horizontal nav returns.
       ========================================================== */
    @media (min-width: 768px) {
        .wrap { padding: 0 40px; }
        .site-nav { padding: 18px 40px; }
        .nav-links {
            display: flex; gap: 24px; position: static; width: auto; height: auto;
            background: none; border: none; box-shadow: none; padding: 0; flex-direction: row;
        }
        .nav-links a { padding: 0; border-bottom: none; font-size: 14px; }
        .hamburger-btn { display: none; }
        .mobile-tabbar { display: none; }
        body { padding-bottom: 0; }
        .footer-grid { grid-template-columns: 1.2fr 1fr 1fr; }
        footer.wrap { padding-bottom: 56px; }
    }

    /* ==========================================================
       DESKTOP — min-width 1024px
       Full editorial layout, wider grids, generous whitespace.
       ========================================================== */
    @media (min-width: 1024px) {
        .wrap { padding: 0 clamp(40px, 5vw, 72px); }
        .site-nav { padding: 20px clamp(40px, 5vw, 72px); }
        .footer-grid { grid-template-columns: 1.4fr 1fr 1fr 1fr; }
    }
</style>

</body>
</html>