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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('review_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('rating')->nullable();
            $table->text('testimonial')->nullable();
            $table->boolean('is_featured')->nullable()->default(false);
            $table->integer('display_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
