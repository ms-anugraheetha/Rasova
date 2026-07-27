<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
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

        if ($variant->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available for that quantity.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $existingItem = $cart->items()->where('product_variant_id', $variant->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $validated['quantity'];
            if ($variant->stock_quantity < $newQuantity) {
                return back()->with('error', 'Not enough stock to add that many.');
            }
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

        if ($cartItem->productVariant->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available.');
        }

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
     * Make sure the cart item actually belongs to the logged-in user's cart —
     * otherwise anyone could edit/delete another user's cart items by guessing IDs.
     */
    protected function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}