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
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->foreign(['category_id'], 'tax_rates_category_id_fkey')->references(['id'])->on('categories')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['category_id'], 'tax_rates_category_id_fkey1')->references(['id'])->on('categories')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            $table->dropForeign('tax_rates_category_id_fkey');
            $table->dropForeign('tax_rates_category_id_fkey1');
        });
    }
};
