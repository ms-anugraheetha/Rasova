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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name', 150)->nullable();
            $table->string('slug', 200)->nullable()->unique('categories_slug_key');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->integer('sort_order')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
