<x-admin-layout>
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500">&larr; Back to orders</a>

    <h1 class="text-2xl font-bold mt-2 mb-6">Order #{{ $order->order_number }}</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Items</h2>
                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm py-2 border-b last:border-0">
                        <span>{{ $item->product_name }} ({{ $item->weight }}) x{{ $item->quantity }}</span>
                        <span>₹{{ number_format($item->total_price_minor / 100, 2) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-bold mt-4 pt-4 border-t">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total_minor / 100, 2) }}</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Shipping Address</h2>
                <p class="text-sm">{{ $order->shipping_full_name }}</p>
                <p class="text-sm">{{ $order->shipping_phone }}</p>
                <p class="text-sm">{{ $order->shipping_address_line_1 }}</p>
                @if ($order->shipping_address_line_2)
                    <p class="text-sm">{{ $order->shipping_address_line_2 }}</p>
                @endif
                <p class="text-sm">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Status History</h2>
                @forelse ($order->statusHistory as $history)
                    <div class="text-sm py-2 border-b last:border-0">
                        <span class="font-medium">{{ ucfirst($history->status) }}</span>
                        <span class="text-gray-500"> — {{ $history->created_at->format('M j, Y g:ia') }}</span>
                        @if ($history->changedBy)
                            <span class="text-gray-500"> by {{ $history->changedBy->first_name }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No status changes recorded.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Update Status</h2>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PATCH')
                    <select name="order_status" class="w-full border rounded px-3 py-2 mb-3">
                        @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'stock_issue'] as $status)
                            <option value="{{ $status }}" @selected($order->order_status == $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded">Update</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Payment</h2>
                <p class="text-sm">Status: {{ ucfirst($order->payment_status) }}</p>
                @if ($order->payment)
                    <p class="text-sm">Method: {{ ucfirst($order->payment->payment_method) }}</p>
                    <p class="text-sm">Gateway ID: {{ $order->payment->gateway_order_id }}</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>