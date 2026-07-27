<x-guest-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Our Pickles</h1>

        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4 mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pickles..."
                   class="border rounded px-3 py-2 flex-1 min-w-[200px]">

            <select name="category" class="border rounded px-3 py-2">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" class="border rounded px-3 py-2">
                <option value="newest" @selected(request('sort', 'newest') == 'newest')>Newest</option>
                <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A-Z</option>
                <option value="name_desc" @selected(request('sort') == 'name_desc')>Name: Z-A</option>
            </select>

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="border rounded-lg p-4 hover:shadow-lg transition">
                    <div class="h-40 bg-gray-100 rounded mb-3 flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                    <h2 class="font-semibold">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                    @if ($product->variants->isNotEmpty())
                        <p class="mt-2 font-bold">
                            ₹{{ number_format($product->variants->first()->price_minor / 100, 2) }}
                        </p>
                    @endif
                </a>
            @empty
                <p class="col-span-full text-gray-500">No products found.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</x-guest-layout>