<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Checkout</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
                @csrf
                <h2 class="font-semibold text-lg">Shipping Address</h2>

                <input type="text" name="full_name" placeholder="Full Name" value="{{ old('full_name') }}"
                       class="w-full border rounded px-3 py-2" required>

                <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}"
                       class="w-full border rounded px-3 py-2" required>

                <input type="text" name="address_line_1" placeholder="Address Line 1" value="{{ old('address_line_1') }}"
                       class="w-full border rounded px-3 py-2" required>

                <input type="text" name="address_line_2" placeholder="Address Line 2 (optional)" value="{{ old('address_line_2') }}"
                       class="w-full border rounded px-3 py-2">

                <input type="text" name="landmark" placeholder="Landmark (optional)" value="{{ old('landmark') }}"
                       class="w-full border rounded px-3 py-2">

                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="city" placeholder="City" value="{{ old('city') }}"
                           class="border rounded px-3 py-2" required>
                    <input type="text" name="state" placeholder="State" value="{{ old('state') }}"
                           class="border rounded px-3 py-2" required>
                </div>

                <input type="text" name="postal_code" placeholder="Postal Code" value="{{ old('postal_code') }}"
                       class="w-full border rounded px-3 py-2" required>

                <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded mt-4">
                    Place Order
                </button>
            </form>

            <div>
                <h2 class="font-semibold text-lg mb-4">Order Summary</h2>
                @foreach ($items as $item)
                    <div class="flex justify-between text-sm mb-2">
                        <span>{{ $item->productVariant->product->name }} ({{ $item->productVariant->weight }}) x{{ $item->quantity }}</span>
                        <span>₹{{ number_format($item->productVariant->price_minor * $item->quantity / 100, 2) }}</span>
                    </div>
                @endforeach
                <div class="border-t mt-4 pt-4 flex justify-between font-bold">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($subtotal / 100, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>