<x-admin-layout>
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500">&larr; Back to products</a>

    <h1 class="text-2xl font-bold mt-2 mb-6">Edit: {{ $product->name }}</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
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

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Product Details</h2>
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <select name="category_id" class="w-full border rounded px-3 py-2">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" @checked($product->is_available) id="is_available">
                        <label for="is_available" class="text-sm">Available for purchase</label>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded">Save Changes</button>
                </form>

                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="mt-4"
                      onsubmit="return confirm('Delete this product and all its variants? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 text-sm">Delete Product</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h2 class="font-semibold mb-4">Images</h2>

                <div class="grid grid-cols-3 gap-3 mb-4">
                    @foreach ($product->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="rounded w-full h-24 object-cover">
                            @if ($image->is_primary)
                                <span class="absolute top-1 left-1 bg-gray-800 text-white text-xs px-2 py-0.5 rounded">Primary</span>
                            @endif
                            <form method="POST" action="{{ route('admin.products.images.destroy', $image->id) }}"
                                  onsubmit="return confirm('Delete this image?');" class="absolute top-1 right-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white text-xs px-2 py-0.5 rounded">✕</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('admin.products.images.upload', $product->id) }}" enctype="multipart/form-data" class="flex gap-2">
                    @csrf
                    <input type="file" name="image" accept="image/*" class="flex-1 border rounded px-3 py-2 text-sm">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Upload</button>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold mb-4">Variants</h2>

                @foreach ($product->variants as $variant)
                    <div class="border rounded p-3 mb-3">
                        <form method="POST" action="{{ route('admin.variants.update', $variant->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                <input type="text" name="weight" value="{{ $variant->weight }}" class="border rounded px-2 py-1 text-sm">
                                <input type="number" step="0.01" name="price" value="{{ $variant->price_minor / 100 }}" class="border rounded px-2 py-1 text-sm">
                                <input type="number" name="stock_quantity" value="{{ $variant->stock_quantity }}" class="border rounded px-2 py-1 text-sm">
                            </div>
                            <div class="flex justify-between items-center">
                                <label class="text-xs flex items-center gap-1">
                                    <input type="checkbox" name="is_active" value="1" @checked($variant->is_active)>
                                    Active
                                </label>
                                <button type="submit" class="text-blue-600 text-sm">Save</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.variants.destroy', $variant->id) }}"
                              onsubmit="return confirm('Delete this variant?');" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-xs">Delete this variant</button>
                        </form>
                    </div>
                @endforeach

                <hr class="my-4">

                <p class="text-sm font-medium mb-2">Add New Variant</p>
                <form method="POST" action="{{ route('admin.variants.store', $product->id) }}" class="grid grid-cols-3 gap-2">
                    @csrf
                    <input type="text" name="weight" placeholder="Weight" class="border rounded px-2 py-1 text-sm" required>
                    <input type="number" step="0.01" name="price" placeholder="Price" class="border rounded px-2 py-1 text-sm" required>
                    <input type="number" name="stock_quantity" placeholder="Stock" value="0" class="border rounded px-2 py-1 text-sm" required>
                    <button type="submit" class="col-span-3 bg-gray-800 text-white py-1 rounded text-sm mt-2">Add Variant</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>