<x-guest-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('products.index') }}" class="text-sm text-gray-500">&larr; Back to all products</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mt-4">{{ session('error') }}</div>
        @endif

        <div class="grid md:grid-cols-2 gap-8 mt-4">
            <div class="h-80 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                No Image
            </div>

            <div>
                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                <p class="text-gray-500">{{ $product->category->name }}</p>

                <p class="mt-4">{{ $product->description ?? 'No description available.' }}</p>

                <div class="mt-6 space-y-2">
                    @foreach ($product->variants as $variant)
                        <div class="border rounded p-3 flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $variant->weight }}</span>
                                
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="font-bold">₹{{ number_format($variant->price_minor / 100, 2) }}</span>
                                @auth
                                    <form method="POST" action="{{ route('cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-gray-800 text-white px-4 py-1 rounded text-sm">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="bg-gray-800 text-white px-4 py-1 rounded text-sm">
                                        Log in to buy
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($product->reviews->isNotEmpty())
            <div class="mt-10">
                <h2 class="text-xl font-semibold mb-4">Reviews</h2>
                @foreach ($product->reviews as $review)
                    <div class="border-b py-3">
                        <p class="font-medium">{{ $review->rating }}/5</p>
                        <p class="text-gray-600">{{ $review->review }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-guest-layout>