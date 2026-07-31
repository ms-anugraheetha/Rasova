<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\CartResolver;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartResolver $cartResolver)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartResolver->resolve($request);
        $items = $cart->items()->with('productVariant.product')->get();

        $subtotal = $items->sum(fn ($item) => $item->productVariant->price_minor * $item->quantity);

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($validated['product_variant_id']);

        $cart = $this->cartResolver->resolve($request);

        $existingItem = $cart->items()->where('product_variant_id', $variant->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $validated['quantity'];

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($request, $cartItem);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($request, $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Item removed.');
    }

    /**
     * Make sure the cart item actually belongs to the current cart —
     * works for both logged-in users and guests, since it compares
     * against the resolved cart rather than assuming a user_id exists.
     */
    protected function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        $cart = $this->cartResolver->resolve($request);

        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }
    }
}