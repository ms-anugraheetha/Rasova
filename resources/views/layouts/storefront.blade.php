<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px;
            padding: 0 4px; box-sizing: border-box; border-radius: 9px;
            background: var(--color-accent); color: #fff; border: 1.5px solid #fff;
            font-size: 11px; font-weight: 700; line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .badge.badge-changed {
            animation: badge-pop 0.3s ease;
        }
        @keyframes badge-pop {
            0% { transform: scale(0.4); opacity: 0; }
            60% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Profile dropdown */
        .profile-dropdown {
            position: absolute; right: 0; top: 52px; min-width: 200px;
            background: var(--color-bg); border: 1px solid var(--color-divider);
            border-radius: 14px; box-shadow: var(--shadow-lg); overflow: hidden; z-index: 70;
            display: flex; flex-direction: column; padding: 6px;
            opacity: 0; visibility: hidden; transform: translateY(-6px) scale(0.98);
            transition: opacity 0.16s ease, transform 0.16s ease, visibility 0.16s;
        }
        .profile-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        .profile-dropdown a, .profile-dropdown .profile-dropdown-soon, .profile-dropdown .profile-dropdown-logout {
            display: block; padding: 10px 12px; font-size: 14px; color: var(--color-text);
            border-radius: 8px; text-align: left; width: 100%; background: none; border: none;
            cursor: pointer; font-family: inherit;
        }
        .profile-dropdown a:hover, .profile-dropdown .profile-dropdown-logout:hover {
            background: color-mix(in srgb, var(--color-text) 6%, transparent);
        }
        .profile-dropdown .profile-dropdown-soon { color: var(--color-text); opacity: 0.4; cursor: default; }
        .profile-dropdown-divider { height: 1px; background: var(--color-divider); margin: 6px 4px; }
        .profile-dropdown-admin { color: var(--color-accent-700); font-weight: 600; }
        .profile-dropdown-logout { color: var(--color-error, #b3132d); }

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
        .nav-links.mobile-open a, .nav-links.mobile-open .nav-link-btn { padding: 12px 4px; font-size: 17px; border-bottom: 1px solid var(--color-divider); }
        .nav-link-btn { all: unset; cursor: pointer; display: block; width: 100%; color: var(--color-text); font-family: inherit; }
        .nav-scrim { display: none; }
        .nav-scrim.show {
            display: block; position: fixed; inset: 0; background: color-mix(in srgb, #000 40%, transparent);
            z-index: 55;
        }

        /* Search overlay */
        /* Guest toast — brief inline messages for guest-only prompts */
        .guest-toast {
            position: fixed; left: 50%; bottom: 90px; transform: translateX(-50%) translateY(20px);
            background: var(--color-accent); color: #fff; padding: 12px 20px; border-radius: 12px;
            font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lg); z-index: 200;
            opacity: 0; pointer-events: none; transition: opacity 0.25s ease, transform 0.25s ease;
            white-space: nowrap; max-width: calc(100vw - 32px); text-overflow: ellipsis; overflow: hidden;
        }
        .guest-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        .search-overlay {
            display: none; position: fixed; inset: 0; z-index: 90;
            background: color-mix(in srgb, #000 45%, transparent);
        }
        .search-overlay.open { display: block; }
        .search-panel {
            background: var(--color-bg); width: 100%; padding: 16px;
            box-shadow: var(--shadow-lg);
            opacity: 0; transform: translateY(-12px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .search-overlay.open .search-panel { opacity: 1; transform: translateY(0); }
        .search-panel-input-row {
            display: flex; align-items: center; gap: 10px;
            border: 1.5px solid var(--color-divider); border-radius: 14px; padding: 0 14px;
            background: var(--color-surface);
        }
        .search-panel-input {
            flex: 1; border: none; background: none; outline: none; color: inherit;
            font-size: 16px; min-height: 52px; font-family: inherit;
        }
        .search-close-btn {
            border: none; background: none; cursor: pointer; color: var(--color-text); opacity: 0.6;
            display: grid; place-items: center; padding: 6px; flex-shrink: 0;
        }
        .search-close-btn:hover { opacity: 1; }

        .search-panel-results { max-height: 60vh; overflow-y: auto; margin-top: 8px; }
        .search-result-item {
            display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 12px;
            text-decoration: none; color: inherit;
        }
        .search-result-item:hover { background: var(--color-surface); }
        .search-result-img { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; flex-shrink: 0; background: var(--color-surface); }
        .search-result-info h4 { font-size: 14px; margin: 0 0 2px; }
        .search-result-info p { font-size: 12px; opacity: 0.6; margin: 0; }
        .search-result-price { margin-left: auto; font-weight: 700; font-size: 14px; white-space: nowrap; }

        .search-no-results { text-align: center; padding: 32px 16px; }
        .search-no-results p { opacity: 0.65; margin: 0 0 16px; font-size: 14px; }
        .search-view-all-row { padding: 10px; }
        .search-view-all-row a { font-size: 13px; font-weight: 600; }

        @media (min-width: 768px) {
            .search-panel { max-width: 560px; margin: 60px auto 0; border-radius: 20px; }
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
        @auth
            <a href="{{ route('orders.index') }}" @if(request()->routeIs('orders.*')) aria-current="page" @endif>Your Orders</a>
        @endauth
    </div>

    <div style="display:flex;gap:6px;align-items:center;">
        <button type="button" class="icon-btn" title="Search" id="searchOpenBtn" style="border:none;background:none;cursor:pointer;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
        </button>

        <a href="{{ route('cart.index') }}" class="icon-btn" title="Cart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            @if(($cartCount ?? 0) > 0)
                <span class="badge" data-cart-badge data-count="{{ $cartCount }}">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
            @endif
        </a>

        @auth
            <div style="position:relative;">
                <button type="button" class="icon-btn" title="Account" id="profileMenuBtn" style="border:none;background:none;cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </button>
                <div id="profileMenu" class="profile-dropdown">
                    <a href="{{ route('profile.edit') }}">My Profile</a>
                    <a href="{{ route('wishlist.index') }}">Wishlist</a>
                    <a href="{{ route('orders.index') }}">My Orders</a>
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="profile-dropdown-admin">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" style="margin-right:6px;vertical-align:-2px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path></svg>
                            Admin Panel
                        </a>
                    @endif
                    <div class="profile-dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="profile-dropdown-logout">Log Out</button>
                    </form>
                </div>
            </div>
        @else
            <div style="position:relative;">
                <button type="button" class="icon-btn" title="Guest Mode" id="guestModeBtn" style="border:none;background:none;cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </button>
                <div id="guestModeMenu" style="display:none;position:absolute;right:0;top:52px;background:var(--color-bg);border:1px solid var(--color-divider);border-radius:12px;box-shadow:var(--shadow-lg);min-width:160px;overflow:hidden;z-index:70;">
                    <a href="{{ route('login') }}" style="display:block;padding:12px 16px;font-size:14px;color:var(--color-text);">Log In</a>
                    <a href="{{ route('register') }}" style="display:block;padding:12px 16px;font-size:14px;color:var(--color-text);border-top:1px solid var(--color-divider);">Create Account</a>
                </div>
            </div>
        @endauth

        <div class="hamburger-btn icon-btn" title="Menu" id="hamburgerBtn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16"></path><path d="M4 6h16"></path><path d="M4 18h16"></path></svg>
        </div>
    </div>
</nav>

<div class="nav-scrim" id="navScrim"></div>

<div class="guest-toast" id="guestToast"></div>

<div class="search-overlay" id="searchOverlay">
    <div class="search-panel" id="searchPanel">
        <div class="search-panel-input-row">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.5;flex-shrink:0;"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            <input type="text" id="searchInput" class="search-panel-input" placeholder="Search products, categories..." autocomplete="off">
            <button type="button" id="searchCloseBtn" class="search-close-btn" aria-label="Close search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </button>
        </div>

        <div class="search-panel-results" id="searchResults"></div>
    </div>
</div>

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
        <span style="position:relative;display:inline-block;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path></svg>
            @if(($cartCount ?? 0) > 0)
                <span class="badge" style="top:-6px;right:-8px;" data-cart-badge data-count="{{ $cartCount }}">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
            @endif
        </span>
        Cart
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
                @auth
                    <a href="{{ route('orders.index') }}">Track order</a>
                @endauth
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

    (function () {
        var guestBtn = document.getElementById('guestModeBtn');
        var guestMenu = document.getElementById('guestModeMenu');
        if (!guestBtn || !guestMenu) return;

        guestBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            guestMenu.style.display = guestMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function () {
            guestMenu.style.display = 'none';
        });
    })();

    (function () {
        var profileBtn = document.getElementById('profileMenuBtn');
        var profileMenu = document.getElementById('profileMenu');
        if (!profileBtn || !profileMenu) return;

        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('open');
        });
        document.addEventListener('click', function () {
            profileMenu.classList.remove('open');
        });
        profileMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    })();

    // Shared guest toast — used by wishlist prompts and similar guest-only messages
    var guestToastTimer = null;
    function showGuestToast(message) {
        var toast = document.getElementById('guestToast');
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(guestToastTimer);
        guestToastTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 2200);
    }

    document.querySelectorAll('.guest-wishlist-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            showGuestToast('Log in to save to wishlist');
        });
    });

    // Search overlay
    (function () {
        var openBtn = document.getElementById('searchOpenBtn');
        var overlay = document.getElementById('searchOverlay');
        var panel = document.getElementById('searchPanel');
        var closeBtn = document.getElementById('searchCloseBtn');
        var input = document.getElementById('searchInput');
        var resultsEl = document.getElementById('searchResults');
        if (!openBtn || !overlay) return;

        var debounceTimer = null;
        var currentController = null;

        function escapeHtml(str) {
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function renderResults(results, term) {
            if (results.length === 0) {
                resultsEl.innerHTML =
                    '<div class="search-no-results">' +
                        '<p>No products found.</p>' +
                        '<a href="{{ route("products.index") }}" class="btn btn-primary">Continue Shopping</a>' +
                    '</div>';
                return;
            }

            var html = results.map(function (item) {
                return '<a href="' + item.url + '" class="search-result-item">' +
                    '<img src="' + item.image + '" alt="' + escapeHtml(item.name) + '" class="search-result-img">' +
                    '<div class="search-result-info">' +
                        '<h4>' + escapeHtml(item.name) + '</h4>' +
                        (item.category ? '<p>' + escapeHtml(item.category) + '</p>' : '') +
                    '</div>' +
                    (item.price ? '<span class="search-result-price">&#8377;' + item.price + '</span>' : '') +
                '</a>';
            }).join('');

            html += '<div class="search-view-all-row"><a href="{{ route("search.results") }}?q=' + encodeURIComponent(term) + '">View all results </a></div>';

            resultsEl.innerHTML = html;
        }

        function search(term) {
            if (currentController) currentController.abort();

            if (!term) {
                resultsEl.innerHTML = '';
                return;
            }

            currentController = new AbortController();

            fetch('{{ route("search.api") }}?q=' + encodeURIComponent(term), { signal: currentController.signal })
                .then(function (res) { return res.json(); })
                .then(function (data) { renderResults(data.results, term); })
                .catch(function (err) { if (err.name !== 'AbortError') resultsEl.innerHTML = ''; });
        }

        function openSearch() {
            overlay.classList.add('open');
            setTimeout(function () { input.focus(); }, 50);
        }

        function closeSearch() {
            overlay.classList.remove('open');
            input.value = '';
            resultsEl.innerHTML = '';
        }

        openBtn.addEventListener('click', openSearch);
        closeBtn.addEventListener('click', closeSearch);

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeSearch();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('open')) closeSearch();
        });

        input.addEventListener('input', function () {
            var term = input.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () { search(term); }, 300);
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var term = input.value.trim();
                if (term) {
                    window.location.href = '{{ route("search.results") }}?q=' + encodeURIComponent(term);
                }
            }
        });
    })();

    // Cart badge: only animate when the count has actually changed since
    // the last page load — otherwise every navigation would replay the
    // pop animation, even when the cart hasn't changed at all.
    (function () {
        var badges = document.querySelectorAll('[data-cart-badge]');
        if (!badges.length) return;

        var currentCount = badges[0].getAttribute('data-count');
        var lastCount = localStorage.getItem('rasova_cart_count');

        if (lastCount !== null && lastCount !== currentCount) {
            badges.forEach(function (badge) {
                badge.classList.add('badge-changed');
            });
        }

        localStorage.setItem('rasova_cart_count', currentCount);
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
        .nav-links a, .nav-links .nav-link-btn { padding: 0; border-bottom: none; font-size: 14px; }
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