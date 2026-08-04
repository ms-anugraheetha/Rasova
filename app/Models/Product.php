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

    /**
     * Per-request memoization for expensive accessors (review_count) that
     * aren't always covered by withCount() — prevents re-querying the same
     * value multiple times within a single page render.
     */
    protected array $memoizedAttributes = [];

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'main_ingredient', 'ingredients', 'shelf_life', 'storage_instructions',
        'dispatch_time', 'image_alt', 'is_available', 'is_hidden', 'featured',
        'best_seller', 'new_arrival', 'seasonal', 'meta_title', 'meta_description', 'average_rating',
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
        if ($this->relationLoaded('variants')) {
            return $this->variants
                ->where('is_active', true)
                ->sortBy('price_minor')
                ->first();
        }

        return $this->variants()
            ->where('is_active', true)
            ->orderBy('price_minor')
            ->first();
    }

    /**
     * Primary product image URL, falling back to first image, then placeholder.
     */
    public function getPrimaryImageUrlAttribute()
    {
        if ($this->relationLoaded('images')) {
            $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        } else {
            $primary = $this->images()->where('is_primary', true)->first()
                ?? $this->images()->first();
        }

        return $primary
            ? asset('storage/' . $primary->image)
            : asset('design/images/placeholder.jpg');
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

    /**
     * Count of publicly-visible reviews (approved + not hidden).
     * Uses the eager-loaded withCount('reviews as review_count') value when
     * present (e.g. on the product grid) to avoid an N+1 query per product;
     * falls back to a live query only when accessed without eager loading.
     */
    public function getReviewCountAttribute(): int
    {
        if (array_key_exists('review_count', $this->attributes)) {
            return (int) $this->attributes['review_count'];
        }

        if (! array_key_exists('review_count', $this->memoizedAttributes)) {
            $this->memoizedAttributes['review_count'] = $this->reviews()->visible()->count();
        }

        return $this->memoizedAttributes['review_count'];
    }

    /**
     * Star-by-star breakdown of visible reviews, e.g. [5 => 12, 4 => 3, 3 => 1, 2 => 0, 1 => 0].
     * Includes percentage of total so templates can render bar widths directly.
     */
    public function ratingBreakdown(): array
    {
        $counts = $this->reviews()->visible()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $total = $counts->sum();

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $counts->get($star, 0);
            $breakdown[$star] = [
                'count' => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        return $breakdown;
    }

    /**
     * Recalculates average_rating from currently-visible reviews (approved + not hidden).
     * Call this after any review is created, edited, approved, rejected, hidden, unhidden, or deleted.
     */
    public function recalculateAverageRating(): void
    {
        $this->update([
            'average_rating' => $this->reviews()->visible()->avg('rating'),
        ]);
    }
}