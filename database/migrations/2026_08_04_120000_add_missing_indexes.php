<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('guest_email');
            $table->index('order_status');
            $table->index('payment_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->index('wishlist_id');
            $table->index('product_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index('product_variant_id');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['guest_email']);
            $table->dropIndex(['order_status']);
            $table->dropIndex(['payment_status']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->dropIndex(['wishlist_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['product_variant_id']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};