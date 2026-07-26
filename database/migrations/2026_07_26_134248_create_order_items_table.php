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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('order_id');
            $table->bigInteger('product_id');
            $table->bigInteger('product_variant_id');
            $table->string('product_name')->nullable();
            $table->string('weight', 50)->nullable();
            $table->bigInteger('unit_price_minor');
            $table->integer('quantity')->nullable();
            $table->bigInteger('total_price_minor');
            $table->decimal('gst_rate_applied', 5)->nullable();
            $table->bigInteger('gst_amount_minor')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
