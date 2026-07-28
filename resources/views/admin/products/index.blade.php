<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded">+ Add Product</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
               class="border rounded px-3 py-2 w-full max-w-sm">
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-500">
                    <th class="p-3">Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Variants</th>
                    <th class="p-3">Total Stock</th>
                    <th class="p-3">Available</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $product->variants->count() }}</td>
                        <td class="p-3">{{ $product->variants->sum('stock_quantity') }}</td>
                        <td class="p-3">{{ $product->is_available ? 'Yes' : 'No' }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-admin-layout>