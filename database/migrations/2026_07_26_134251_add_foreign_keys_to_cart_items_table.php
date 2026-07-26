<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['cart_id'], 'cart_items_cart_id_fkey')->references(['id'])->on('carts')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['cart_id'], 'cart_items_cart_id_fkey1')->references(['id'])->on('carts')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_variant_id'], 'cart_items_product_variant_id_fkey')->references(['id'])->on('product_variants')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_variant_id'], 'cart_items_product_variant_id_fkey1')->references(['id'])->on('product_variants')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('cart_items_cart_id_fkey');
            $table->dropForeign('cart_items_cart_id_fkey1');
            $table->dropForeign('cart_items_product_variant_id_fkey');
            $table->dropForeign('cart_items_product_variant_id_fkey1');
        });
    }
};
