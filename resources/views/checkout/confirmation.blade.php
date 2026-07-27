<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8 text-center">
        <h1 class="text-2xl font-bold mb-4">Thank you for your order!</h1>
        <p class="text-gray-600 mb-6">Order #{{ $order->order_number }} has been placed and is awaiting payment confirmation.</p>

        <div class="border rounded p-6 text-left">
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm mb-2">
                    <span>{{ $item->product_name }} ({{ $item->weight }}) x{{ $item->quantity }}</span>
                    <span>₹{{ number_format($item->total_price_minor / 100, 2) }}</span>
                </div>
            @endforeach
            <div class="border-t mt-4 pt-4 flex justify-between font-bold">
                <span>Total</span>
                <span>₹{{ number_format($order->total_minor / 100, 2) }}</span>
            </div>
        </div>

        <p class="text-sm text-gray-500 mt-6">
            Note: Payment gateway integration is still in development, so this order is currently marked "pending" and won't auto-confirm yet.
        </p>
    </div>
</x-app-layout>