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
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->foreign(['product_id'], 'wishlist_items_product_id_fkey')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'], 'wishlist_items_product_id_fkey1')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['wishlist_id'], 'wishlist_items_wishlist_id_fkey')->references(['id'])->on('wishlists')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['wishlist_id'], 'wishlist_items_wishlist_id_fkey1')->references(['id'])->on('wishlists')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->dropForeign('wishlist_items_product_id_fkey');
            $table->dropForeign('wishlist_items_product_id_fkey1');
            $table->dropForeign('wishlist_items_wishlist_id_fkey');
            $table->dropForeign('wishlist_items_wishlist_id_fkey1');
        });
    }
};
