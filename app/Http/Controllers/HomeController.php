<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::where('status', true)->orderBy('sort_order')->get();

        [$bestsellers, $showBestsellerPlaceholder] = $this->resolveBestsellers();

        $wishlistedProductIds = auth()->check()
            ? WishlistItem::whereHas('wishlist', fn ($q) => $q->where('user_id', auth()->id()))
                ->pluck('product_id')
                ->flip()
            : collect();

        return view('home', [
            'categories' => $categories,
            'bestsellers' => $bestsellers,
            'showBestsellerPlaceholder' => $showBestsellerPlaceholder,
            'footerCategories' => $categories,
            'testimonials' => $this->latestFeaturedReviews(),
            'wishlistedProductIds' => $wishlistedProductIds,
        ]);
    }

    /**
     * Real best sellers, calculated from completed (paid) orders — ranked by
     * total quantity sold, then by number of distinct orders. If the store
     * has no completed orders yet, falls back to the latest 4 available
     * products and flags the view to show an honest "coming soon" note
     * instead of faking sales data.
     */
    protected function resolveBestsellers(): array
    {
        $productLoads = [
            'variants' => fn ($q) => $q->where('is_active', true)->orderBy('price_minor'),
            'images',
        ];
        $reviewCountLoad = ['reviews as review_count' => fn ($q) => $q->where('status', 'approved')->where('is_hidden', false)];

        $rankedProductIds = OrderItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('COUNT(DISTINCT order_id) as order_count')
            ->whereHas('order', fn ($q) => $q->where('payment_status', 'paid'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->orderByDesc('order_count')
            ->take(4)
            ->pluck('product_id');

        if ($rankedProductIds->isNotEmpty()) {
            $products = Product::whereIn('id', $rankedProductIds)
                ->where('is_available', true)
                ->with($productLoads)
                ->withCount($reviewCountLoad)
                ->get();

            // Re-order to match the ranking, since whereIn() doesn't preserve it.
            $ordered = $rankedProductIds
                ->map(fn ($id) => $products->firstWhere('id', $id))
                ->filter()
                ->values();

            if ($ordered->isNotEmpty()) {
                return [$ordered, false];
            }
        }

        $fallback = Product::where('is_available', true)
            ->with($productLoads)
            ->withCount($reviewCountLoad)
            ->latest()
            ->take(4)
            ->get();

        return [$fallback, true];
    }

    /**
     * The latest 3 reviews to feature on the homepage — prioritizing
     * highly-rated reviews when there are enough of them, falling back to
     * the most recent visible reviews overall otherwise.
     */
    protected function latestFeaturedReviews()
    {
        $featured = Review::visible()
            ->where('rating', '>=', 4)
            ->with(['user', 'product'])
            ->latest()
            ->take(3)
            ->get();

        if ($featured->count() < 3) {
            $remaining = 3 - $featured->count();

            $filler = Review::visible()
                ->whereNotIn('id', $featured->pluck('id'))
                ->with(['user', 'product'])
                ->latest()
                ->take($remaining)
                ->get();

            $featured = $featured->merge($filler)->sortByDesc('created_at')->values();
        }

        return $featured;
    }
}