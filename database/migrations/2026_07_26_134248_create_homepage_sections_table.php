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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('section_key', 100)->nullable()->unique('homepage_sections_section_key_key');
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->boolean('is_visible')->nullable()->default(true);
            $table->integer('display_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
