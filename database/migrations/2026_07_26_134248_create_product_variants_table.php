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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('product_id');
            $table->string('weight', 50)->nullable();
            $table->bigInteger('price_minor');
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->nullable()->default(5);
            $table->string('sku', 120)->nullable()->unique('product_variants_sku_key');
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
