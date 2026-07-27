<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'category' => 'Mango Pickle',
                'product' => 'Traditional Mango Pickle',
                'description' => 'A classic Kerala-style mango pickle made with raw mangoes, red chilli, and mustard oil, aged for authentic depth of flavor.',
                'variants' => [
                    ['weight' => '250g', 'price' => 180, 'stock' => 30],
                    ['weight' => '500g', 'price' => 320, 'stock' => 20],
                ],
            ],
            [
                'category' => 'Lemon Pickle',
                'product' => 'Lemon Pickle',
                'description' => 'Tangy and spicy lemon pickle, slow-cooked with a blend of traditional spices for a bright, zesty kick.',
                'variants' => [
                    ['weight' => '250g', 'price' => 160, 'stock' => 30],
                    ['weight' => '500g', 'price' => 290, 'stock' => 20],
                ],
            ],
            [
                'category' => 'Prawns Pickle',
                'product' => 'Spicy Prawns Pickle',
                'description' => 'Fresh prawns cooked in a rich, spicy masala and preserved in oil — a premium non-vegetarian favorite from the Kerala coast.',
                'variants' => [
                    ['weight' => '200g', 'price' => 350, 'stock' => 15],
                    ['weight' => '400g', 'price' => 650, 'stock' => 10],
                ],
            ],
            [
                'category' => 'Kondattam',
                'product' => 'Kondattam Pickle',
                'description' => 'Traditional sun-dried and fried Kerala kondattam, crispy and tangy — a beloved side dish and snack.',
                'variants' => [
                    ['weight' => '200g', 'price' => 140, 'stock' => 25],
                ],
            ],
        ];

        foreach ($items as $sortOrder => $item) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($item['category'])],
                [
                    'name' => $item['category'],
                    'status' => true,
                    'sort_order' => $sortOrder + 1,
                ]
            );

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $item['product'],
                'slug' => Str::slug($item['product']) . '-' . Str::random(6),
                'description' => $item['description'],
                'is_available' => true,
            ]);

            foreach ($item['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'weight' => $variant['weight'],
                    'price_minor' => $variant['price'] * 100, // convert rupees to paise
                    'stock_quantity' => $variant['stock'],
                    'sku' => strtoupper(Str::slug($item['product'], '')) . '-' . strtoupper($variant['weight']),
                    'is_active' => true,
                ]);
            }

            $this->command->info("Created: {$item['product']} ({$category->name}) with " . count($item['variants']) . " variant(s)");
        }
    }
}