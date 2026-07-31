<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Rasova Admin</title>

    <link rel="stylesheet" href="{{ asset('design/_ds/organic/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('design/rasova-theme.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--color-bg); color: var(--color-text); }
        a { color: inherit; text-decoration: none; }

        .admin-shell { display: flex; min-height: 100vh; }

        .admin-sidebar {
            width: 220px; flex-shrink: 0; background: var(--color-text); color: var(--color-bg);
            display: flex; flex-direction: column;
        }
        .admin-sidebar-brand {
            padding: 20px; font-family: var(--font-heading); font-weight: 700; font-size: 18px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .admin-nav { padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; flex: 1; }
        .admin-nav a, .admin-nav button {
            display: block; padding: 10px 12px; border-radius: 10px; font-size: 14px;
            background: none; border: none; text-align: left; width: 100%; cursor: pointer;
            color: rgba(255,255,255,0.75); font-family: inherit;
        }
        .admin-nav a:hover, .admin-nav button:hover { background: rgba(255,255,255,0.08); color: var(--color-bg); }
        .admin-nav a.active { background: var(--color-accent); color: white; }
        .admin-nav-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,0.12); }
        .admin-nav-footer a, .admin-nav-footer button {
            display: block; padding: 10px 12px; border-radius: 10px; font-size: 13px;
            background: none; border: none; text-align: left; width: 100%; cursor: pointer;
            color: rgba(255,255,255,0.55); font-family: inherit;
        }
        .admin-nav-footer a:hover, .admin-nav-footer button:hover { color: var(--color-bg); }

        .admin-main { flex: 1; padding: 32px 40px; min-width: 0; }
        .admin-main h1 { font-size: 26px; margin: 0 0 24px; }

        .admin-flash-success { background: color-mix(in srgb, green 12%, transparent); color: green; padding: 10px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .admin-flash-error { background: color-mix(in srgb, var(--color-error, #b3132d) 12%, transparent); color: var(--color-error, #b3132d); padding: 10px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }

        .admin-card { background: var(--color-surface); border-radius: 16px; padding: 24px; }
        .admin-card h2 { font-size: 15px; margin: 0 0 16px; }

        .admin-stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .admin-stat-card { background: var(--color-surface); border-radius: 16px; padding: 18px 20px; }
        .admin-stat-card p:first-child { font-size: 13px; opacity: 0.6; margin: 0 0 6px; }
        .admin-stat-card p:last-child { font-size: 24px; font-weight: 700; margin: 0; font-family: var(--font-heading); }

        .admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .admin-table th { text-align: left; padding: 10px 12px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.03em; opacity: 0.55; border-bottom: 1px solid var(--color-divider); }
        .admin-table td { padding: 12px; border-bottom: 1px solid var(--color-divider); }
        .admin-table tr:last-child td { border-bottom: none; }

        .admin-input, .admin-select, .admin-textarea {
            width: 100%; min-height: 44px; padding: 0 12px; border-radius: 10px;
            border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 14px; font-family: inherit;
        }
        .admin-textarea { min-height: 90px; padding: 10px 12px; resize: vertical; }
        .admin-field { margin-bottom: 14px; }
        .admin-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .admin-check-row { display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 14px; }
        .admin-check-row input { width: 16px; height: 16px; }

        .admin-btn-link { color: var(--color-accent-700); font-size: 13px; background: none; border: none; cursor: pointer; padding: 0; font-family: inherit; }
        .admin-btn-danger { color: var(--color-error, #b3132d); }

        @yield('extra-styles')
    </style>
</head>
<body>

<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">Rasova Admin</div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
        </nav>
        <div class="admin-nav-footer">
            <a href="{{ route('products.index') }}">&larr; Back to store</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Log out</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if (session('success'))
            <p class="admin-flash-success">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="admin-flash-error">{{ $error }}</p>
            @endforeach
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')

</body>
</html>