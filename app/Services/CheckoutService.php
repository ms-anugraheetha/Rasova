<?php

namespace App\Services;

use App\Exceptions\OversellException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Creates a pending order from the user's cart. Does NOT touch stock —
     * stock is only decremented once payment is confirmed (see PaymentWebhookService).
     */
    public function checkout(User $user, Address $address, string $paymentMethod = 'upi'): Order
    {
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $items = $cart->items()->with('productVariant.product')->get();

        if ($items->isEmpty()) {
            throw new \RuntimeException('Cart is empty.');
        }

        // Read-only availability check — reassures the customer stock exists right now.
        // This is NOT a lock/reservation; stock can still be taken by someone else
        // before payment completes. The webhook re-checks with a real lock.
        foreach ($items as $cartItem) {
            $variant = $cartItem->productVariant;
            if ($variant->stock_quantity < $cartItem->quantity) {
                throw new OversellException($variant->id, $cartItem->quantity, $variant->stock_quantity);
            }
        }

        $subtotal = $items->sum(fn ($i) => $i->productVariant->price_minor * $i->quantity);
        $shippingFee = 0; // TODO: plug in ShippingRule lookup here later
        $gstAmount = 0;   // TODO: plug in TaxRate lookup here later
        $total = $subtotal + $shippingFee + $gstAmount;

        return \DB::transaction(function () use ($user, $address, $items, $subtotal, $shippingFee, $gstAmount, $total, $paymentMethod, $cart) {
            $order = Order::create([
                'order_number' => 'RSV-' . strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'subtotal_minor' => $subtotal,
                'shipping_fee_minor' => $shippingFee,
                'gst_amount_minor' => $gstAmount,
                'total_minor' => $total,
                'shipping_full_name' => $address->full_name,
                'shipping_phone' => $address->phone,
                'shipping_address_line_1' => $address->address_line_1,
                'shipping_address_line_2' => $address->address_line_2,
                'shipping_landmark' => $address->landmark,
                'shipping_city' => $address->city,
                'shipping_state' => $address->state,
                'shipping_country' => $address->country,
                'shipping_postal_code' => $address->postal_code,
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);

            foreach ($items as $cartItem) {
                $variant = $cartItem->productVariant;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'weight' => $variant->weight,
                    'unit_price_minor' => $variant->price_minor,
                    'quantity' => $cartItem->quantity,
                    'total_price_minor' => $variant->price_minor * $cartItem->quantity,
                ]);
            }

            $razorpay = new \Razorpay\Api\Api(
                config('services.razorpay.key_id'),
                 config('services.razorpay.key_secret')
                 );
                 $razorpayOrder = $razorpay->order->create([
                    'receipt' => $order->order_number,
                    'amount' => $total, // Razorpay expects amount in the smallest currency unit (paise) — matches our price_minor convention
                    'currency' => 'INR',
                    ]);
                    Payment::create([
                        'order_id' => $order->id,
                        'payment_method' => $paymentMethod,
                        'gateway' => 'razorpay',
                        'gateway_order_id' => $razorpayOrder['id'],
                        'amount_minor' => $total,
                        'status' => 'pending',
                        ]);


            // Cart is cleared once the order is placed, regardless of payment outcome —
            // if payment fails, the order itself is marked failed; items aren't re-added to cart automatically.
            $cart->items()->delete();

            return $order->fresh(['items', 'payment']);
        });
    }
}