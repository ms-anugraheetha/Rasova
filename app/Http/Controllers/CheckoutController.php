<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartResolver;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public const INDIAN_STATES = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa',
        'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala',
        'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland',
        'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
    ];

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

        $savedAddresses = $request->user()
            ? Address::where('user_id', $request->user()->id)->orderByDesc('is_default')->orderByDesc('created_at')->get()
            : collect();

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'savedAddresses' => $savedAddresses,
            'indianStates' => self::INDIAN_STATES,
        ]);
    }

    public function store(Request $request, CheckoutService $checkoutService)
    {
        $rules = [
            'address_type' => 'required|in:home,office,other',
            'full_name' => 'required|string|max:255',
            'phone' => ['required', 'digits:10'],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => ['required', Rule::in(self::INDIAN_STATES)],
            'postal_code' => ['required', 'digits:6'],
            'selected_address_id' => 'nullable|integer',
            'editing_address_id' => 'nullable|integer',
        ];

        // Guests need an email on file to receive order updates/receipts —
        // logged-in users already have one on their account.
        if (! $request->user()) {
            $rules['email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        $shipping = [
            'full_name' => trim($validated['full_name']),
            'phone' => $validated['phone'],
            'address_line_1' => trim($validated['address_line_1']),
            'address_line_2' => isset($validated['address_line_2']) ? trim($validated['address_line_2']) : null,
            'city' => trim($validated['city']),
            'district' => trim($validated['district']),
            'state' => $validated['state'],
            'country' => 'India',
            'postal_code' => $validated['postal_code'],
            'address_type' => $validated['address_type'],
        ];

        $addressId = null;
        $guestEmail = null;
        $guestPhone = null;

        if ($request->user()) {
            if ($request->filled('editing_address_id')) {
                // Editing a previously saved address — update it in place.
                $address = Address::where('user_id', $request->user()->id)
                    ->findOrFail($validated['editing_address_id']);
                $address->update($shipping);
                $addressId = $address->id;
            } elseif ($request->boolean('save_address')) {
                // Brand new address, and the customer wants it kept for next time.
                $address = Address::create(array_merge($shipping, [
                    'user_id' => $request->user()->id,
                    'is_default' => false,
                ]));
                $addressId = $address->id;
            } elseif ($request->filled('selected_address_id')) {
                // Using an existing saved address as-is — just reference it,
                // no need to write anything since nothing changed.
                $existing = Address::where('user_id', $request->user()->id)
                    ->find($validated['selected_address_id']);
                $addressId = $existing?->id;
            }
            // Otherwise: a new address used only for this order, not saved to the address book.

            if ($request->boolean('set_default') && $addressId) {
                Address::where('user_id', $request->user()->id)
                    ->where('id', '!=', $addressId)
                    ->update(['is_default' => false]);
                Address::where('id', $addressId)->update(['is_default' => true]);
            }
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
     * Client-triggered payment success handler — called from the Razorpay
     * checkout JS `handler` callback. This is a fallback/primary path for
     * local development, since Razorpay's server-to-server webhook can't
     * reach a local Docker container without a public tunnel (ngrok etc.).
     * In production, the webhook remains the authoritative source of truth;
     * this just makes local testing actually work end-to-end.
     */
    public function confirmPayment(Request $request, int $orderId, \App\Services\PaymentWebhookService $webhookService)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $order = Order::with('payment')->findOrFail($orderId);
        $this->authorizeOrderAccess($request, $order);

        if (!$order->payment || $order->payment->gateway_order_id !== $validated['razorpay_order_id']) {
            return response()->json(['verified' => false], 422);
        }

        try {
            $api = new \Razorpay\Api\Api(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['verified' => false, 'message' => $e->getMessage()], 422);
        }

        $webhookService->markPaid($order->id, $validated['razorpay_payment_id'], $order->total_minor);

        return response()->json(['verified' => true]);
    }

    /**
     * Client-triggered payment failure handler — called from Razorpay's
     * `payment.failed` JS event, for the same local-dev webhook-reachability
     * reason as confirmPayment() above.
     */
    public function failPayment(Request $request, int $orderId, \App\Services\PaymentWebhookService $webhookService)
    {
        $order = Order::findOrFail($orderId);
        $this->authorizeOrderAccess($request, $order);

        $webhookService->markFailed($order->id, $request->input('razorpay_payment_id'));

        return response()->json(['status' => 'recorded']);
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