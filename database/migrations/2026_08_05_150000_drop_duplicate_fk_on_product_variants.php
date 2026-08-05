<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The 2026_07_26_134251_add_foreign_keys_to_product_variants_table migration
     * accidentally defined the same foreign key twice (product_variants_product_id_fkey
     * and product_variants_product_id_fkey1), both on product_id -> products.id.
     * This drops the redundant duplicate, keeping the original.
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign('product_variants_product_id_fkey1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreign(['product_id'], 'product_variants_product_id_fkey1')
                ->references(['id'])->on('products')
                ->onUpdate('no action')->onDelete('no action');
        });
    }
};