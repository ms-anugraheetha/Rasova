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
        Schema::create('products', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('category_id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique('products_slug_key');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('main_ingredient')->nullable();
            $table->text('ingredients')->nullable();
            $table->string('shelf_life', 100)->nullable();
            $table->text('storage_instructions')->nullable();
            $table->string('dispatch_time', 100)->nullable();
            $table->string('image_alt')->nullable();
            $table->decimal('average_rating', 3)->nullable()->default(0);
            $table->integer('total_reviews')->nullable()->default(0);
            $table->boolean('is_available')->nullable()->default(true);
            $table->boolean('is_hidden')->nullable()->default(false);
            $table->boolean('featured')->nullable()->default(false);
            $table->boolean('best_seller')->nullable()->default(false);
            $table->boolean('new_arrival')->nullable()->default(false);
            $table->boolean('seasonal')->nullable()->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
