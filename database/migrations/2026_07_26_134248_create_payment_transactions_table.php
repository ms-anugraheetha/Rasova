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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('payment_id');
            $table->string('transaction_type', 50)->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->bigInteger('amount_minor');
            $table->string('gateway_event_id')->nullable()->unique('payment_transactions_gateway_event_id_key');
            $table->string('response_code', 100)->nullable();
            $table->text('response_message')->nullable();
            $table->text('gateway_response')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
