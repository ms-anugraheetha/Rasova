@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<h1>Add product</h1>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-card" style="max-width:560px;">
    @csrf

    <div class="admin-field">
        <label class="admin-label">Category</label>
        <select name="category_id" class="admin-select">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="admin-field">
        <label class="admin-label">Product name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="admin-input" required>
    </div>

    <div class="admin-field">
        <label class="admin-label">Description</label>
        <textarea name="description" rows="3" class="admin-textarea">{{ old('description') }}</textarea>
    </div>

    <div class="admin-check-row">
        <input type="checkbox" name="is_available" value="1" checked id="is_available">
        <label for="is_available">Available for purchase</label>
    </div>

    <div class="admin-field">
        <label class="admin-label">Product image (optional)</label>
        <input type="file" name="image" accept="image/*" class="admin-input" style="padding:8px 12px;">
    </div>

    <hr style="border:none;border-top:1px solid var(--color-divider);margin:20px 0;">

    <p style="font-size:13px;opacity:0.65;margin-bottom:16px;">Add the first variant (weight/price/stock). You can add more variants after creating the product.</p>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
        <div class="admin-field">
            <label class="admin-label">Weight</label>
            <input type="text" name="weight" placeholder="e.g. 250g" class="admin-input" required>
        </div>
        <div class="admin-field">
            <label class="admin-label">Price (&#8377;)</label>
            <input type="number" step="0.01" name="price" class="admin-input" required>
        </div>
        <div class="admin-field">
            <label class="admin-label">Stock quantity</label>
            <input type="number" name="stock_quantity" value="0" class="admin-input" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="min-height:44px;padding:0 28px;margin-top:8px;">Create product</button>
</form>

@endsection