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
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->foreign(['payment_id'], 'payment_transactions_payment_id_fkey')->references(['id'])->on('payments')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['payment_id'], 'payment_transactions_payment_id_fkey1')->references(['id'])->on('payments')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropForeign('payment_transactions_payment_id_fkey');
            $table->dropForeign('payment_transactions_payment_id_fkey1');
        });
    }
};
