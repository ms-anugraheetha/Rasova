<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'main_ingredient', 'ingredients', 'shelf_life', 'storage_instructions',
        'dispatch_time', 'image_alt', 'is_available', 'is_hidden', 'featured',
        'best_seller', 'new_arrival', 'seasonal', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'average_rating' => 'decimal:2',
            'is_available' => 'boolean',
            'is_hidden' => 'boolean',
            'featured' => 'boolean',
            'best_seller' => 'boolean',
            'new_arrival' => 'boolean',
            'seasonal' => 'boolean',
        ];
    }

    public function getDefaultVariantAttribute()
    {
    return $this->variants()
        ->where('is_active', true)
        ->orderBy('price_minor')
        ->first();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}