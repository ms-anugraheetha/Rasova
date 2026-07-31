@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <h1 style="margin:0;">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="min-height:44px;padding:0 20px;display:inline-flex;align-items:center;">+ Add product</a>
</div>

<form method="GET" style="margin-bottom:20px;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="admin-input" style="max-width:360px;">
</form>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Variants</th>
                <th>Total stock</th>
                <th>Available</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->variants->count() }}</td>
                    <td>{{ $product->variants->sum('stock_quantity') }}</td>
                    <td>{{ $product->is_available ? 'Yes' : 'No' }}</td>
                    <td><a href="{{ route('admin.products.edit', $product->id) }}" class="admin-btn-link">Edit</a></td>
                </tr>
            @empty
                <tr><td colspan="6" style="opacity:0.6;">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $products->links() }}
</div>

@endsection