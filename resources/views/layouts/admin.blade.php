<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} — Rasova Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-56 bg-gray-900 text-white flex-shrink-0">
            <div class="p-4 font-bold text-lg border-b border-gray-700">Rasova Admin</div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" class="block py-2 px-3 rounded hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800' : '' }}">Orders</a>
                <a href="{{ route('admin.products.index') }}" class="block py-2 px-3 rounded hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800' : '' }}">Products</a>
                <div class="border-t border-gray-700 mt-4 pt-4">
                    <a href="{{ route('products.index') }}" class="block py-2 px-3 rounded hover:bg-gray-800 text-sm text-gray-400">&larr; Back to store</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block py-2 px-3 rounded hover:bg-gray-800 text-sm text-gray-400 w-full text-left">Log out</button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>