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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign(['approved_by'], 'reviews_approved_by_fkey')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['approved_by'], 'reviews_approved_by_fkey1')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['order_id'], 'reviews_order_id_fkey')->references(['id'])->on('orders')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['order_id'], 'reviews_order_id_fkey1')->references(['id'])->on('orders')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'], 'reviews_product_id_fkey')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['product_id'], 'reviews_product_id_fkey1')->references(['id'])->on('products')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'reviews_user_id_fkey')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'reviews_user_id_fkey1')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_approved_by_fkey');
            $table->dropForeign('reviews_approved_by_fkey1');
            $table->dropForeign('reviews_order_id_fkey');
            $table->dropForeign('reviews_order_id_fkey1');
            $table->dropForeign('reviews_product_id_fkey');
            $table->dropForeign('reviews_product_id_fkey1');
            $table->dropForeign('reviews_user_id_fkey');
            $table->dropForeign('reviews_user_id_fkey1');
        });
    }
};
