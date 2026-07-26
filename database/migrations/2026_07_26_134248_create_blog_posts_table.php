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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique('blog_posts_slug_key');
            $table->string('cover_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->bigInteger('author_id')->nullable();
            $table->string('status', 20)->nullable()->default('draft');
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('blog_posts');
    }
};
