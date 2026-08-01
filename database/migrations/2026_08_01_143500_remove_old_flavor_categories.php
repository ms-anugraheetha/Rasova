<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $keep = Category::whereIn('slug', ['veg', 'non-veg'])->pluck('id');

        Category::whereNotIn('id', $keep)->get()->each(function (Category $category) {
            // Safety check: only delete if nothing still references it. If a
            // product was missed during manual reassignment, this leaves that
            // category in place instead of silently orphaning the product.
            if ($category->products()->count() === 0) {
                $category->delete();
            } else {
                \Log::warning("Skipped deleting category '{$category->name}' — still has {$category->products()->count()} product(s) assigned.");
            }
        });
    }

    public function down(): void
    {
        // Not reversible — the original category data is gone.
    }
};