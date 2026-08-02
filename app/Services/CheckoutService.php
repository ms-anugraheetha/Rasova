<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Creates a pending order from the given cart. Does NOT touch stock —
     * stock is only decremented once payment is confirmed (see PaymentWebhookService).
     *
     * Works for both logged-in users and guests:
     * - Logged-in: pass $user and (optionally) $addressId if the shipping
     *   details were saved to an Address record.
     * - Guest: pass $user = null and $guestEmail / $guestPhone; no
     *   Address record is created since addresses.user_id is required.
     *
     * @param  array{full_name:string, phone:string, address_line_1:string, address_line_2?:string|null, landmark?:string|null, city:string, state:string, country?:string, postal_code:string}  $shipping
     */
    public function checkout(
        Cart $cart,
        ?User $user,
        array $shipping,
        ?int $addressId = null,
        ?string $guestEmail = null,
        ?string $guestPhone = null,
        string $paymentMethod = 'upi'
    ): Order {
        $items = $cart->items()->with('productVariant.product')->get();

        if ($items->isEmpty()) {
            throw new \RuntimeException('Cart is empty.');
        }

        $subtotal = $items->sum(fn ($i) => $i->productVariant->price_minor * $i->quantity);
        $shippingFee = 0; // TODO: plug in ShippingRule lookup here later
        $gstAmount = 0;   // TODO: plug in TaxRate lookup here later
        $total = $subtotal + $shippingFee + $gstAmount;

        return \DB::transaction(function () use (
            $user, $shipping, $addressId, $guestEmail, $guestPhone,
            $items, $subtotal, $shippingFee, $gstAmount, $total, $paymentMethod, $cart
        ) {
            $order = Order::create([
                'order_number' => 'RSV-' . strtoupper(Str::random(10)),
                'user_id' => $user?->id,
                'address_id' => $addressId,
                'guest_email' => $guestEmail,
                'guest_phone' => $guestPhone,
                'subtotal_minor' => $subtotal,
                'shipping_fee_minor' => $shippingFee,
                'gst_amount_minor' => $gstAmount,
                'total_minor' => $total,
                'shipping_full_name' => $shipping['full_name'],
                'shipping_phone' => $shipping['phone'],
                'shipping_address_line_1' => $shipping['address_line_1'],
                'shipping_address_line_2' => $shipping['address_line_2'] ?? null,
                'shipping_landmark' => $shipping['landmark'] ?? null,
                'shipping_city' => $shipping['city'],
                'shipping_district' => $shipping['district'] ?? null,
                'shipping_state' => $shipping['state'],
                'shipping_country' => $shipping['country'] ?? 'India',
                'shipping_postal_code' => $shipping['postal_code'],
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