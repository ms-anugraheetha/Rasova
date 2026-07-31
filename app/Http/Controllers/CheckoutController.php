<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartResolver;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(protected CartResolver $cartResolver)
    {
    }

    public function show(Request $request)
    {
        $cart = $this->cartResolver->resolve($request);
        $items = $cart->items()->with('productVariant.product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn ($item) => $item->productVariant->price_minor * $item->quantity);

        return view('checkout.index', compact('items', 'subtotal'));
    }

    public function store(Request $request, CheckoutService $checkoutService)
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
        ];

        // Guests need an email on file to receive order updates/receipts —
        // logged-in users already have one on their account.
        if (! $request->user()) {
            $rules['email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        $shipping = [
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'address_line_1' => $validated['address_line_1'],
            'address_line_2' => $validated['address_line_2'] ?? null,
            'landmark' => $validated['landmark'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => 'India',
            'postal_code' => $validated['postal_code'],
        ];

        $addressId = null;
        $guestEmail = null;
        $guestPhone = null;

        if ($request->user()) {
            // Save to the user's address book so it can be reused next time.
            $address = Address::create(array_merge($shipping, [
                'user_id' => $request->user()->id,
                'address_type' => 'home',
                'is_default' => false,
            ]));
            $addressId = $address->id;
        } else {
            $guestEmail = $validated['email'];
            $guestPhone = $validated['phone'];
        }

        $cart = $this->cartResolver->resolve($request);

        try {
            $order = $checkoutService->checkout(
                cart: $cart,
                user: $request->user(),
                shipping: $shipping,
                addressId: $addressId,
                guestEmail: $guestEmail,
                guestPhone: $guestPhone,
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Guests have no account to scope orders to, so we remember which
        // order IDs belong to this browser session for the payment/confirmation
        // pages to check against — prevents guests from viewing each other's orders.
        if (! $request->user()) {
            $guestOrderIds = $request->session()->get('guest_order_ids', []);
            $guestOrderIds[] = $order->id;
            $request->session()->put('guest_order_ids', $guestOrderIds);
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Request $request, int $orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        $this->authorizeOrderAccess($request, $order);

        return view('checkout.payment', [
            'order' => $order,
            'razorpayKeyId' => config('services.razorpay.key_id'),
        ]);
    }

    public function confirmation(Request $request, int $orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        $this->authorizeOrderAccess($request, $order);

        return view('checkout.confirmation', compact('order'));
    }

    /**
     * Logged-in users can only view their own orders; guests can only view
     * orders placed in their current session (tracked via guest_order_ids).
     */
    protected function authorizeOrderAccess(Request $request, Order $order): void
    {
        if ($request->user()) {
            abort_unless($order->user_id === $request->user()->id, 403);

            return;
        }

        $guestOrderIds = $request->session()->get('guest_order_ids', []);
        abort_unless(in_array($order->id, $guestOrderIds, true), 403);
    }
}