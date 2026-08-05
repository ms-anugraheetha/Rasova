<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enforces "one review per account per product" at the database level.
     * Previously only enforced in app code via firstOrNew(), which is a
     * check-then-act race: two simultaneous review submissions from the same
     * user could both pass the "does a review exist?" check and both insert,
     * silently creating two review rows with no error. Postgres treats NULLs
     * as distinct for unique constraints, so guest reviews (user_id IS NULL)
     * are unaffected and multiple guests can still each leave their own review.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['product_id', 'user_id'], 'reviews_product_id_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_product_id_user_id_unique');
        });
    }
};