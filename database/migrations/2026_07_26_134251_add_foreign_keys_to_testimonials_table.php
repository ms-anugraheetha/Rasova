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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreign(['review_id'], 'testimonials_review_id_fkey')->references(['id'])->on('reviews')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['review_id'], 'testimonials_review_id_fkey1')->references(['id'])->on('reviews')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign('testimonials_review_id_fkey');
            $table->dropForeign('testimonials_review_id_fkey1');
        });
    }
};
