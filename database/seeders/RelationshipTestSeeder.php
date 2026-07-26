<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipTestSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedAndVerify();
        });
    }

    protected function seedAndVerify(): void
    {
        $this->command->info('--- Creating test data ---');

        $uniquePhone = '9' . random_int(100000000, 999999999);

        // User + Address
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'reltest_' . uniqid() . '@example.com',
            'password' => 'password',
            'phone' => $uniquePhone,
            'status' => 'active',
            'is_admin' => false,
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'full_name' => 'Test User',
            'phone' => $uniquePhone,
            'address_line_1' => '123 Test Street',
            'city' => 'Kochi',
            'state' => 'Kerala',
            'country' => 'India',
            'postal_code' => '682001',
            'address_type' => 'home',
            'is_default' => true,
        ]);

        // Category + Product + Variant
        $category = Category::create([
            'name' => 'Test Mango Pickles',
            'slug' => 'test-mango-pickles-' . uniqid(),
            'status' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Spicy Mango Pickle',
            'slug' => 'test-spicy-mango-' . uniqid(),
            'is_available' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'weight' => '250g',
            'price_minor' => 25000, // ₹250.00
            'stock_quantity' => 50,
            'sku' => 'TESTSKU-' . uniqid(),
            'is_active' => true,
        ]);

        // Cart + CartItem
        $cart = Cart::create(['user_id' => $user->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Wishlist + WishlistItem
        $wishlist = Wishlist::create(['user_id' => $user->id]);
        $wishlistItem = WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $product->id,
        ]);

        // Order + OrderItem + Payment + Transaction + StatusHistory
        $order = Order::create([
            'order_number' => 'TEST-' . uniqid(),
            'user_id' => $user->id,
            'address_id' => $address->id,
            'subtotal_minor' => 50000,
            'shipping_fee_minor' => 5000,
            'gst_amount_minor' => 2500,
            'total_minor' => 57500,
            'shipping_full_name' => $address->full_name,
            'shipping_phone' => $address->phone,
            'shipping_address_line_1' => $address->address_line_1,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
            'shipping_postal_code' => $address->postal_code,
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'weight' => $variant->weight,
            'unit_price_minor' => $variant->price_minor,
            'quantity' => 2,
            'total_price_minor' => $variant->price_minor * 2,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'upi',
            'gateway' => 'razorpay',
            'amount_minor' => $order->total_minor,
            'status' => 'paid',
        ]);

        $transaction = PaymentTransaction::create([
            'payment_id' => $payment->id,
            'transaction_type' => 'payment',
            'gateway_event_id' => 'evt_test_' . uniqid(),
            'amount_minor' => $order->total_minor,
        ]);

        $statusHistory = OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'confirmed',
            'changed_by' => $user->id,
        ]);

        // Review
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'review' => 'Test review content',
            'verified_purchase' => true,
            'status' => 'approved',
        ]);

        $this->command->info('--- Test data created. Now verifying relationships ---');

        $this->command->line('User -> Addresses: ' . $user->addresses()->count());
        $this->command->line('User -> Orders: ' . $user->orders()->count());
        $this->command->line('User -> Wishlists: ' . $user->wishlists()->count());
        $this->command->line('User -> Reviews: ' . $user->reviews()->count());

        $this->command->line('Category -> Products: ' . $category->products()->count());
        $this->command->line('Product -> Category name: ' . $product->category->name);
        $this->command->line('Product -> Variants: ' . $product->variants()->count());

        $this->command->line('Cart -> Items: ' . $cart->items()->count());
        $this->command->line('CartItem -> ProductVariant weight: ' . $cartItem->productVariant->weight);

        $this->command->line('Order -> Items: ' . $order->items()->count());
        $this->command->line('Order -> Payment gateway: ' . $order->payment->gateway);
        $this->command->line('Order -> User email: ' . $order->user->email);
        $this->command->line('Order -> Address city: ' . $order->address->city);
        $this->command->line('Order -> StatusHistory count: ' . $order->statusHistory()->count());

        $this->command->line('Payment -> Transactions: ' . $payment->transactions()->count());
        $this->command->line('OrderItem -> Product name: ' . $orderItem->product->name);

        $this->command->line('Review -> Product name: ' . $review->product->name);
        $this->command->line('Review -> User email: ' . $review->user->email);

        $this->command->info('--- All relationships resolved without error. Cleaning up test data ---');

        // Clean up so this doesn't leave junk data behind
        $review->delete();
        $statusHistory->delete();
        $transaction->delete();
        $payment->delete();
        $orderItem->delete();
        $order->delete();
        $wishlistItem->delete();
        $wishlist->delete();
        $cartItem->delete();
        $cart->delete();
        $variant->delete();
        $product->delete();
        $category->delete();
        $address->delete();
        $user->delete();

        $this->command->info('--- Cleanup done ---');
    }
}