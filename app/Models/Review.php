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
        'user_id', 'guest_name', 'guest_email', 'is_anonymous', 'product_id', 'order_id', 'rating', 'title', 'review',
        'verified_purchase', 'legacy_review', 'status', 'is_hidden', 'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_purchase' => 'boolean',
            'legacy_review' => 'boolean',
            'is_hidden' => 'boolean',
            'is_anonymous' => 'boolean',
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
     * Public display name — "Anonymous" if the reviewer chose to hide their
     * name, otherwise their account name or the name they typed as a guest.
     */
    public function getReviewerNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user?->full_name ?? $this->guest_name ?? 'Anonymous';
    }

    /**
     * The real name behind the review, regardless of the anonymous setting —
     * for admin reference only. Never expose this in public-facing views.
     */
    public function getInternalReviewerNameAttribute(): string
    {
        return $this->user?->full_name ?? $this->guest_name ?? 'Unknown';
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