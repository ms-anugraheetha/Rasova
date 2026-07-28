<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Orders</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-4 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order number..."
               class="border rounded px-3 py-2 flex-1">

        <select name="status" class="border rounded px-3 py-2">
            <option value="">All Statuses</option>
            @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'stock_issue'] as $status)
                <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="p-3">Order #</th>
                    <th class="p-3">Customer</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Payment</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Date</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t">
                        <td class="p-3">{{ $order->order_number }}</td>
                        <td class="p-3">{{ $order->user->first_name ?? 'N/A' }}</td>
                        <td class="p-3">{{ ucfirst($order->order_status) }}</td>
                        <td class="p-3">{{ ucfirst($order->payment_status) }}</td>
                        <td class="p-3">₹{{ number_format($order->total_minor / 100, 2) }}</td>
                        <td class="p-3">{{ $order->created_at->format('M j, Y') }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</x-admin-layout>