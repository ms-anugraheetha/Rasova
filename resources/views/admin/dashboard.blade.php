<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Pending Orders</p>
            <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Revenue (Paid)</p>
            <p class="text-2xl font-bold">₹{{ number_format($totalRevenue / 100, 2) }}</p>
        </div>
        
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm mb-8">
        <h2 class="font-semibold mb-4">Orders — Last 7 Days</h2>
        <canvas id="ordersChart" height="80"></canvas>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm">
        <h2 class="font-semibold mb-4">Recent Orders</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-2">Order #</th>
                    <th class="pb-2">Customer</th>
                    <th class="pb-2">Status</th>
                    <th class="pb-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td class="py-2">{{ $order->user->first_name ?? 'N/A' }}</td>
                        <td class="py-2">{{ ucfirst($order->order_status) }}</td>
                        <td class="py-2">₹{{ number_format($order->total_minor / 100, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-gray-500">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        const ctx = document.getElementById('ordersChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($ordersPerDay->pluck('date')) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($ordersPerDay->pluck('count')) !!},
                    borderColor: '#1f2937',
                    backgroundColor: 'rgba(31, 41, 55, 0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
</x-admin-layout>