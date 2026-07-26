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
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('product_id');
            $table->bigInteger('order_id')->nullable();
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->boolean('verified_purchase')->nullable()->default(false);
            $table->boolean('legacy_review')->nullable()->default(false);
            $table->string('status', 30)->nullable()->default('pending');
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
