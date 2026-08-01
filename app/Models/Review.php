<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'product_id', 'order_id', 'rating', 'title', 'review',
        'verified_purchase', 'legacy_review', 'status', 'is_hidden', 'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_purchase' => 'boolean',
            'legacy_review' => 'boolean',
            'is_hidden' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpfulVote::class);
    }

    public function reply(): HasOne
    {
        return $this->hasOne(ReviewReply::class);
    }

    /**
     * Publicly visible: approved by moderation AND not hidden by an admin afterward.
     * These are two independent controls — "hide" lets an admin temporarily
     * pull an already-approved review without losing its approval history.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', 'approved')->where('is_hidden', false);
    }

    public function getHelpfulCountAttribute(): int
    {
        return $this->helpfulVotes()->count();
    }
}