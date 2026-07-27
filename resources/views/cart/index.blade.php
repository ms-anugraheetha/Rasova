<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @forelse ($items as $item)
            <div class="border rounded p-4 flex justify-between items-center mb-3">
                <div>
                    <p class="font-semibold">{{ $item->productVariant->product->name }}</p>
                    <p class="text-sm text-gray-500">{{ $item->productVariant->weight }}</p>
                </div>

                <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                           class="w-16 border rounded px-2 py-1">
                    <button type="submit" class="text-sm text-blue-600">Update</button>
                </form>

                <p class="font-bold">₹{{ number_format($item->productVariant->price_minor * $item->quantity / 100, 2) }}</p>

                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600">Remove</button>
                </form>
            </div>
        @empty
            <p class="text-gray-500">Your cart is empty.</p>
        @endforelse

        @if ($items->isNotEmpty())
            <div class="mt-6 flex justify-between items-center border-t pt-4">
                <p class="text-xl font-bold">Subtotal: ₹{{ number_format($subtotal / 100, 2) }}</p>
                <a href="#" class="bg-gray-800 text-white px-6 py-2 rounded">Checkout</a>
            </div>
        @endif
    </div>
</x-app-layout>