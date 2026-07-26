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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('order_number', 50)->nullable()->unique('orders_order_number_key');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('address_id')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->bigInteger('subtotal_minor');
            $table->bigInteger('shipping_fee_minor')->default(0);
            $table->bigInteger('gst_amount_minor')->default(0);
            $table->bigInteger('total_minor');
            $table->string('shipping_full_name', 150)->nullable();
            $table->string('shipping_phone', 20)->nullable();
            $table->string('shipping_address_line_1')->nullable();
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_landmark')->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_country', 100)->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('payment_status', 30)->nullable()->default('pending');
            $table->string('order_status', 50)->nullable()->default('placed');
            $table->string('tracking_number', 100)->nullable();
            $table->string('courier_name', 100)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->boolean('gift_order')->nullable()->default(false);
            $table->text('gift_message')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
