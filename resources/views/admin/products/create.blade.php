<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Add Product</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-2xl">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Product Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_available" value="1" checked id="is_available">
            <label for="is_available" class="text-sm">Available for purchase</label>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Product Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
        </div>

        <hr>

        <p class="text-sm text-gray-500">Add the first variant (weight/price/stock). You can add more variants after creating the product.</p>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Weight</label>
                <input type="text" name="weight" placeholder="e.g. 250g" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Price (₹)</label>
                <input type="number" step="0.01" name="price" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="0" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded">Create Product</button>
    </form>
</x-admin-layout>