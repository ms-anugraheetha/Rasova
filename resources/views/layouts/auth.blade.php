<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rasova')</title>

    <link rel="stylesheet" href="{{ asset('design/_ds/organic/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('design/rasova-theme.css') }}">

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; background: var(--color-bg); color: var(--color-text);
            min-height: 100vh; display: flex; flex-direction: column;
        }
        a { color: var(--color-accent-700); }
        a:hover { color: var(--color-accent-600); }

        .wrap { width: 100%; max-width: 1440px; margin: 0 auto; padding: 0 20px; }

        .auth-topbar {
            padding: 20px; display: flex; justify-content: center;
        }
        .auth-topbar a { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .auth-topbar img { width: 40px; height: 33px; border-radius: 50%; }
        .auth-topbar span { font-family: var(--font-heading); font-weight: 700; font-size: 18px; color: var(--color-text); }

        main { flex: 1; display: flex; }

        @yield('extra-styles')
    </style>
    @stack('head')
</head>
<body>

<div class="auth-topbar">
    <a href="{{ route('home') }}">
        <img src="{{ asset('design/assets/rasova-logo.png') }}" alt="Rasova">
        <span>Rasova</span>
    </a>
</div>

<main>
    @yield('content')
</main>

@stack('scripts')

</body>
</html>