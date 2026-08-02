<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    /**
     * Real uploaded image if set (via admin), otherwise falls back to a
     * convention-based path (public/design/categories/{slug}.jpg) so images
     * can be dropped in directly without an admin upload UI, then a generic
     * placeholder if neither exists.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $conventionPath = 'design/categories/' . $this->slug . '.' . $extension;
            if (file_exists(public_path($conventionPath))) {
                return asset($conventionPath);
            }
        }

        return asset('design/placeholder-category.jpg');
    }
}