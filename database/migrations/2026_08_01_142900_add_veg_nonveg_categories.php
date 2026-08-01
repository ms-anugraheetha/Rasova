<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Category::firstOrCreate(
            ['slug' => 'veg'],
            ['name' => 'Veg', 'status' => true, 'sort_order' => 1]
        );

        Category::firstOrCreate(
            ['slug' => 'non-veg'],
            ['name' => 'Non-Veg', 'status' => true, 'sort_order' => 2]
        );
    }

    public function down(): void
    {
        Category::whereIn('slug', ['veg', 'non-veg'])->delete();
    }
};