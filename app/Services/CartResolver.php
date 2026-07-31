<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartResolver
{
    /**
     * Get the current cart for this request — the authenticated user's cart,
     * or a session-scoped guest cart if not logged in. If the user just
     * logged in and had a guest cart in their session, its items are merged
     * into their account cart (matching quantities are summed) and the
     * guest cart is discarded.
     */
    public function resolve(Request $request): Cart
    {
        if ($request->user()) {
            $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
            $this->mergeGuestCartInto($request, $cart);

            return $cart;
        }

        $guestCartId = $request->session()->get('guest_cart_id');

        if ($guestCartId) {
            $cart = Cart::find($guestCartId);

            if ($cart) {
                return $cart;
            }
        }

        // Note: we store the cart's own primary key in the session, not
        // Laravel's session ID — the session ID changes on login
        // (session fixation protection), which would otherwise orphan the cart.
        $cart = Cart::create(['session_id' => $request->session()->getId()]);
        $request->session()->put('guest_cart_id', $cart->id);

        return $cart;
    }

    protected function mergeGuestCartInto(Request $request, Cart $userCart): void
    {
        $guestCartId = $request->session()->pull('guest_cart_id');

        if (! $guestCartId || $guestCartId === $userCart->id) {
            return;
        }

        $guestCart = Cart::find($guestCartId);

        if (! $guestCart) {
            return;
        }

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()->where('product_variant_id', $item->product_variant_id)->first();

            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $item->quantity]);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }
}
