<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_available', true)
            ->with(['category', 'variants' => function ($q) {
                $q->where('is_active', true)->orderBy('price_minor');
            }]);

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->input('search') . '%');
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'price_asc' => $query->orderBy(
                \App\Models\ProductVariant::select('price_minor')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price_minor')
                    ->limit(1)
            ),
            'price_desc' => $query->orderByDesc(
                \App\Models\ProductVariant::select('price_minor')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price_minor')
                    ->limit(1)
            ),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_available', true)
            ->with([
                'category',
                'variants' => fn ($q) => $q->where('is_active', true)->orderBy('price_minor'),
                'images' => fn ($q) => $q->orderBy('sort_order'),
                'reviews' => fn ($q) => $q->where('status', 'approved')->latest(),
            ])
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}