<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Services\CartResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request, CartResolver $cartResolver): View
    {
        $categories = Category::where('status', true)->orderBy('sort_order')->get();

        $bestsellers = Product::query()
            ->where('is_available', true)
            ->where('best_seller', true)
            ->with(['variants' => fn ($q) => $q->where('is_active', true)->orderBy('price_minor'), 'images'])
            ->withCount(['reviews as review_count' => fn ($q) => $q->where('status', 'approved')->where('is_hidden', false)])
            ->take(4)
            ->get();

        $cart = $cartResolver->resolve($request);
        $cartCount = $cart->items()->sum('quantity');

        $wishlistedProductIds = auth()->check()
            ? \App\Models\WishlistItem::whereHas('wishlist', fn ($q) => $q->where('user_id', auth()->id()))
                ->pluck('product_id')
                ->flip()
            : collect();

        return view('home', [
            'categories' => $categories,
            'bestsellers' => $bestsellers,
            'footerCategories' => $categories,
            'cartCount' => $cartCount,
            'testimonials' => $this->selectTestimonials(),
            'wishlistedProductIds' => $wishlistedProductIds,
        ]);
    }

    /**
     * Picks 3–5 real reviews to feature on the homepage — weighted toward
     * highly-rated verified reviews, with a couple of other authentic ones
     * mixed in for variety. Randomized on every load (no caching), so a
     * page reload genuinely shows a different set, per spec.
     */
    protected function selectTestimonials()
    {
        $count = random_int(3, 5);
        $featuredCount = max(1, $count - 1); // leave at least one slot for "other authentic" reviews

        $featured = Review::visible()
            ->where('verified_purchase', true)
            ->where('rating', '>=', 4)
            ->with(['user', 'product'])
            ->inRandomOrder()
            ->take($featuredCount)
            ->get();

        $remaining = $count - $featured->count();

        if ($remaining > 0) {
            $filler = Review::visible()
                ->whereNotIn('id', $featured->pluck('id'))
                ->with(['user', 'product'])
                ->inRandomOrder()
                ->take($remaining)
                ->get();

            $featured = $featured->merge($filler);
        }

        return $featured->shuffle();
    }
}