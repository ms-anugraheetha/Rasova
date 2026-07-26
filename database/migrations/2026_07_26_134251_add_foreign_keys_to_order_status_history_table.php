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
        Schema::table('order_status_history', function (Blueprint $table) {
            $table->foreign(['changed_by'], 'order_status_history_changed_by_fkey')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['changed_by'], 'order_status_history_changed_by_fkey1')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['order_id'], 'order_status_history_order_id_fkey')->references(['id'])->on('orders')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['order_id'], 'order_status_history_order_id_fkey1')->references(['id'])->on('orders')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_history', function (Blueprint $table) {
            $table->dropForeign('order_status_history_changed_by_fkey');
            $table->dropForeign('order_status_history_changed_by_fkey1');
            $table->dropForeign('order_status_history_order_id_fkey');
            $table->dropForeign('order_status_history_order_id_fkey1');
        });
    }
};
