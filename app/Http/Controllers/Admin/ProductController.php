<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_available', true)
            ->with(['category', 'variants' => function ($q) {
                $q->where('is_active', true)->orderBy('price_minor');
            }])
            ->withCount(['reviews as review_count' => function ($q) {
                $q->where('status', 'approved')->where('is_hidden', false);
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

        $wishlistedProductIds = auth()->check()
            ? \App\Models\WishlistItem::whereHas('wishlist', fn ($q) => $q->where('user_id', auth()->id()))
                ->pluck('product_id')
                ->flip()
            : collect();

        return view('products.index', compact('products', 'categories', 'wishlistedProductIds'));
    }

    public function show(string $slug, Request $request)
    {
        $product = Product::where('slug', $slug)
            ->where('is_available', true)
            ->with([
                'category',
                'variants' => fn ($q) => $q->where('is_active', true)->orderBy('price_minor'),
                'images' => fn ($q) => $q->orderBy('sort_order'),
            ])
            ->firstOrFail();

        $reviews = $product->reviews()
            ->visible()
            ->with(['user', 'images', 'reply.admin'])
            ->withCount('helpfulVotes')
            ->latest()
            ->paginate(5, ['*'], 'reviews_page');

        $ratingBreakdown = $product->ratingBreakdown();

        $userReview = null;
        if ($request->user()) {
            $userReview = Review::where('product_id', $product->id)
                ->where('user_id', $request->user()->id)
                ->first();
        }

        $helpfulVoteIds = $request->user()
            ? \App\Models\ReviewHelpfulVote::where('user_id', $request->user()->id)
                ->whereIn('review_id', $reviews->pluck('id'))
                ->pluck('review_id')
                ->flip()
            : collect();

        $inWishlist = $request->user()
            ? \App\Models\WishlistItem::where('product_id', $product->id)
                ->whereHas('wishlist', fn ($q) => $q->where('user_id', $request->user()->id))
                ->exists()
            : false;

        return view('products.show', compact(
            'product', 'reviews', 'ratingBreakdown', 'userReview', 'helpfulVoteIds', 'inWishlist'
        ));
    }
}