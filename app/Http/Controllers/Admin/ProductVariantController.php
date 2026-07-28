<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'weight' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'weight' => $validated['weight'],
            'price_minor' => (int) round($validated['price'] * 100),
            'stock_quantity' => $validated['stock_quantity'],
            'sku' => strtoupper(Str::slug($product->name, '')) . '-' . strtoupper(Str::random(6)),
            'is_active' => true,
        ]);

        return back()->with('success', 'Variant added.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'weight' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $variant->update([
            'weight' => $validated['weight'],
            'price_minor' => (int) round($validated['price'] * 100),
            'stock_quantity' => $validated['stock_quantity'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Variant updated.');
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();

        return back()->with('success', 'Variant removed.');
    }
}