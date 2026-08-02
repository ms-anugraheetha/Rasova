@extends('layouts.admin')

@section('title', 'Edit ' . $product->name)

@section('extra-styles')
.admin-edit-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }
.admin-image-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
.admin-image-tile { position: relative; }
.admin-image-tile img { width: 100%; height: 96px; object-fit: cover; border-radius: 10px; }
.admin-image-primary-badge { position: absolute; top: 6px; left: 6px; background: var(--color-text); color: var(--color-bg); font-size: 10px; padding: 2px 8px; border-radius: 6px; }
.admin-image-delete-btn { position: absolute; top: 6px; right: 6px; background: var(--color-error, #b3132d); color: white; border: none; width: 20px; height: 20px; border-radius: 50%; font-size: 11px; cursor: pointer; }
.admin-variant-card { border: 1px solid var(--color-divider); border-radius: 12px; padding: 12px; margin-bottom: 12px; }
.admin-variant-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 8px; }
.admin-variant-row input { min-height: 38px; padding: 0 10px; border-radius: 8px; border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 13px; }
.admin-variant-footer { display: flex; justify-content: space-between; align-items: center; }
.admin-variant-footer label { font-size: 12px; display: flex; align-items: center; gap: 6px; }
.admin-new-variant-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
.admin-new-variant-grid input { min-height: 38px; padding: 0 10px; border-radius: 8px; border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 13px; }
@endsection

@section('content')

<a href="{{ route('admin.products.index') }}" class="admin-btn-link"> Back to products</a>
<h1 style="margin-top:8px;">Edit: {{ $product->name }}</h1>

<div class="admin-edit-layout">
    <div>
        <div class="admin-card" style="margin-bottom:20px;">
            <h2>Product details</h2>
            <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                @csrf
                @method('PATCH')

                <div class="admin-field">
                    <label class="admin-label">Category</label>
                    <select name="category_id" class="admin-select">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Product name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="admin-input" required>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Description</label>
                    <textarea name="description" rows="3" class="admin-textarea">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="admin-check-row">
                    <input type="checkbox" name="is_available" value="1" @checked($product->is_available) id="is_available">
                    <label for="is_available">Available for purchase</label>
                </div>

                <button type="submit" class="btn btn-primary" style="min-height:44px;padding:0 24px;">Save changes</button>
            </form>

            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" style="margin-top:16px;"
                  onsubmit="return confirm('Delete this product and all its variants? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn-link admin-btn-danger">Delete product</button>
            </form>
        </div>

        <div class="admin-card">
            <h2>Images</h2>

            <div class="admin-image-grid">
                @foreach ($product->images as $image)
                    <div class="admin-image-tile">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }}">
                        @if ($image->is_primary)
                            <span class="admin-image-primary-badge">Primary</span>
                        @endif
                        <form method="POST" action="{{ route('admin.products.images.destroy', $image->id) }}"
                              onsubmit="return confirm('Delete this image?');" style="position:absolute;top:6px;right:6px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-image-delete-btn">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admin.products.images.upload', $product->id) }}" enctype="multipart/form-data" style="display:flex;gap:8px;">
                @csrf
                <input type="file" name="image" accept="image/*" class="admin-input" style="flex:1;padding:8px 12px;">
                <button type="submit" class="btn btn-primary" style="min-height:44px;padding:0 20px;">Upload</button>
            </form>
        </div>
    </div>

    <div class="admin-card">
        <h2>Variants</h2>

        @foreach ($product->variants as $variant)
            <div class="admin-variant-card">
                <form method="POST" action="{{ route('admin.variants.update', $variant->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="admin-variant-row">
                        <input type="text" name="weight" value="{{ $variant->weight }}">
                        <input type="number" step="0.01" name="price" value="{{ $variant->price_minor / 100 }}">
                        <input type="number" name="stock_quantity" value="{{ $variant->stock_quantity }}">
                    </div>
                    <div class="admin-variant-footer">
                        <label>
                            <input type="checkbox" name="is_active" value="1" @checked($variant->is_active)>
                            Active
                        </label>
                        <button type="submit" class="admin-btn-link">Save</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.variants.destroy', $variant->id) }}"
                      onsubmit="return confirm('Delete this variant?');" style="margin-top:8px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-link admin-btn-danger" style="font-size:12px;">Delete this variant</button>
                </form>
            </div>
        @endforeach

        <hr style="border:none;border-top:1px solid var(--color-divider);margin:16px 0;">

        <p style="font-size:13px;font-weight:600;margin-bottom:10px;">Add new variant</p>
        <form method="POST" action="{{ route('admin.variants.store', $product->id) }}" class="admin-new-variant-grid">
            @csrf
            <input type="text" name="weight" placeholder="Weight" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="stock_quantity" placeholder="Stock" value="0" required>
            <button type="submit" class="btn btn-primary" style="grid-column:1/-1;min-height:38px;margin-top:6px;">Add variant</button>
        </form>
    </div>
</div>

@endsection