<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => $request->user()->id]);

        $products = $wishlist->items()
            ->with(['product' => function ($q) {
                $q->with(['variants' => fn ($v) => $v->where('is_active', true)->orderBy('price_minor'), 'images'])
                    ->withCount(['reviews as review_count' => fn ($r) => $r->where('status', 'approved')->where('is_hidden', false)]);
            }])
            ->get()
            ->pluck('product')
            ->filter(); // drop any item whose product was since deleted

        return view('wishlist.index', compact('products'));
    }

    /**
     * Toggles a product in/out of the current user's wishlist.
     * Returns JSON so the heart button can update instantly without a page reload.
     * Checking for an existing item first prevents duplicate entries.
     */
    public function toggle(Request $request, Product $product)
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => $request->user()->id]);

        $item = $wishlist->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->delete();
            $inWishlist = false;
        } else {
            $wishlist->items()->create(['product_id' => $product->id]);
            $inWishlist = true;
        }

        return response()->json(['in_wishlist' => $inWishlist]);
    }
}