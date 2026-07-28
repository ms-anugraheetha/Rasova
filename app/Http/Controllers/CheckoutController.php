<?php

namespace App\Http\Controllers;


use App\Models\Address;
use App\Models\Cart;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = $cart->items()->with('productVariant.product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn ($item) => $item->productVariant->price_minor * $item->quantity);

        return view('checkout.index', compact('items', 'subtotal'));
    }

    public function store(Request $request, CheckoutService $checkoutService)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ]);

        $address = Address::create([
            'user_id' => $request->user()->id,
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'address_line_1' => $validated['address_line_1'],
            'address_line_2' => $validated['address_line_2'] ?? null,
            'landmark' => $validated['landmark'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => 'India',
            'postal_code' => $validated['postal_code'],
            'address_type' => 'home',
            'is_default' => false,
        ]);

        try {
            $order = $checkoutService->checkout($request->user(), $address);
        }  catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Request $request, int $orderId)
    {
        $order = $request->user()->orders()->with('payment')->findOrFail($orderId);

        return view('checkout.payment', [
            'order' => $order,
            'razorpayKeyId' => config('services.razorpay.key_id'),
        ]);
    }

    public function confirmation(Request $request, int $orderId)
    {
        $order = $request->user()->orders()->with('items')->findOrFail($orderId);

        return view('checkout.confirmation', compact('order'));
    }
}