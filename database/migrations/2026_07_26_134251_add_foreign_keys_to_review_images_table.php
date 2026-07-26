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
        Schema::table('review_images', function (Blueprint $table) {
            $table->foreign(['review_id'], 'review_images_review_id_fkey')->references(['id'])->on('reviews')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['review_id'], 'review_images_review_id_fkey1')->references(['id'])->on('reviews')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_images', function (Blueprint $table) {
            $table->dropForeign('review_images_review_id_fkey');
            $table->dropForeign('review_images_review_id_fkey1');
        });
    }
};
