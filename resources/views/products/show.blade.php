@extends('layouts.storefront')

@section('title', $product->name . ' — Rasova')

@section('extra-styles')
.pdp-breadcrumb { font-size: 13px; opacity: 0.6; padding: 16px 0 0; }
.pdp-layout { padding: 24px 0 56px; display: flex; flex-direction: column; gap: 28px; }

.pdp-gallery-main { border-radius: 20px; overflow: hidden; aspect-ratio: 1; background: var(--color-surface); }
.pdp-gallery-main img { width: 100%; height: 100%; object-fit: cover; }
.pdp-thumbs { display: flex; gap: 10px; margin-top: 10px; overflow-x: auto; }
.pdp-thumbs button {
    width: 64px; height: 64px; border-radius: 12px; overflow: hidden; flex-shrink: 0;
    border: 2px solid transparent; padding: 0; cursor: pointer; background: var(--color-surface);
}
.pdp-thumbs button.active { border-color: var(--color-accent); }
.pdp-thumbs img { width: 100%; height: 100%; object-fit: cover; }

.pdp-info h1 { font-size: clamp(24px, 6vw, 34px); margin: 4px 0 6px; }
.pdp-cat { font-size: 13px; opacity: 0.6; }
.pdp-desc { font-size: 15px; opacity: 0.78; line-height: 1.6; margin: 16px 0; }

.variant-group { display: flex; flex-direction: column; gap: 10px; margin: 20px 0; }
.variant-option {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px; border-radius: 14px; border: 1.5px solid var(--color-divider);
    cursor: pointer;
}
.variant-option.selected { border-color: var(--color-accent); background: var(--color-accent-2-100); }
.variant-option input { display: none; }
.variant-weight { font-weight: 600; font-size: 14px; }
.variant-stock { font-size: 12px; opacity: 0.6; margin-top: 2px; }
.variant-price { font-weight: 700; font-size: 15px; }

.pdp-add-form { margin-top: 8px; }
.pdp-add-form .btn { width: 100%; min-height: 48px; }
.pdp-secondary-actions { display: flex; gap: 10px; margin-top: 10px; }
.pdp-secondary-actions .btn { flex: 1; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px; }
.pdp-wishlist-btn.active { border-color: var(--color-accent); color: var(--color-accent); background: var(--color-accent-2-100); }

.reviews-section { padding-top: 40px; border-top: 1px solid var(--color-divider); margin-top: 8px; }
.reviews-section h2 { font-size: clamp(20px, 5vw, 26px); margin: 0 0 20px; }
.review-item { padding: 20px 0; border-bottom: 1px solid var(--color-divider); }
.review-item:last-child { border-bottom: none; }
.review-stars { display: inline-flex; gap: 2px; color: var(--color-star); margin-bottom: 6px; }
.review-item p { margin: 0; font-size: 14px; opacity: 0.8; }

/* Rating summary + breakdown */
.rating-summary { display: flex; flex-direction: column; gap: 20px; margin-bottom: 32px; }
.rating-summary-headline { display: flex; align-items: baseline; gap: 12px; }
.rating-summary-headline .big-number { font-size: 42px; font-weight: 700; font-family: var(--font-heading); line-height: 1; }
.rating-summary-headline .out-of { font-size: 15px; opacity: 0.6; }
.rating-breakdown { display: flex; flex-direction: column; gap: 6px; max-width: 320px; }
.rating-bar-row { display: flex; align-items: center; gap: 10px; font-size: 12px; opacity: 0.75; }
.rating-bar-row span:first-child { width: 32px; flex-shrink: 0; }
.rating-bar-track { flex: 1; height: 8px; border-radius: 6px; background: var(--color-divider); overflow: hidden; }
.rating-bar-fill { height: 100%; background: var(--color-star); border-radius: 6px; }
.rating-bar-row span:last-child { width: 28px; text-align: right; flex-shrink: 0; }

/* Review card */
.review-header-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.review-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.review-name-col h4 { font-size: 14px; margin: 0; }
.verified-badge { font-size: 11px; background: var(--color-accent-2-100); padding: 2px 8px; border-radius: 6px; margin-left: 6px; white-space: nowrap; }
.review-date { font-size: 12px; opacity: 0.5; margin-left: auto; white-space: nowrap; }
.review-title { font-weight: 600; font-size: 14px; margin: 4px 0 4px !important; opacity: 1 !important; }

.review-images-grid { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.review-image-thumb { width: 64px; height: 64px; border-radius: 10px; object-fit: cover; cursor: pointer; }

.helpful-row { display: flex; align-items: center; gap: 10px; margin-top: 12px; }
.helpful-btn {
    display: inline-flex; align-items: center; gap: 6px; font-size: 12px;
    background: none; border: 1px solid var(--color-divider); border-radius: 20px;
    padding: 6px 12px; cursor: pointer; color: inherit; font-family: inherit;
}
.helpful-btn.active { border-color: var(--color-accent); color: var(--color-accent); background: var(--color-accent-2-100); }

.seller-reply { margin-top: 12px; padding: 12px 14px; border-radius: 12px; background: var(--color-surface); }
.seller-reply-label { font-size: 12px; font-weight: 700; margin-bottom: 4px; display: block; }

.review-pagination { display: flex; justify-content: center; gap: 6px; margin-top: 28px; }
.review-pagination a, .review-pagination span { min-width: 36px; height: 36px; display: grid; place-items: center; border-radius: 8px; font-size: 13px; }
.review-pagination a { background: var(--color-surface); }
.review-pagination .active { background: var(--color-accent); color: white; }

.reviews-empty-state { text-align: center; padding: 40px 20px; background: var(--color-surface); border-radius: 16px; margin-bottom: 32px; }
.reviews-empty-state p { opacity: 0.7; margin-bottom: 4px !important; }

/* Write/edit review form */
.write-review-block { margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--color-divider); max-width: 520px; }
.review-form-collapsible {
    max-height: 0; overflow: hidden;
    transition: max-height 0.4s ease, opacity 0.3s ease;
    opacity: 0;
}
.review-form-collapsible.open { opacity: 1; }
.review-form-inner { padding-top: 4px; }
.review-anon-check {
    display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 12px; cursor: pointer;
}
.review-anon-check input { width: 16px; height: 16px; }
.review-form-actions { display: flex; gap: 10px; align-items: center; }
.write-review-block h3 { font-size: 16px; margin: 0 0 14px; }
.review-star-picker { display: flex; gap: 6px; margin-bottom: 14px; }
.review-star-btn { background: none; border: none; padding: 0; cursor: pointer; color: var(--color-star); opacity: 0.35; }
.review-star-btn.filled { opacity: 1; }
.review-star-btn svg { fill: none; }
.review-star-btn.filled svg { fill: currentColor; }
.review-title-input {
    width: 100%; min-height: 44px; padding: 0 12px; border-radius: 10px; margin-bottom: 12px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 14px; font-family: inherit;
}
.review-textarea {
    width: 100%; min-height: 100px; padding: 12px; border-radius: 12px; margin-bottom: 12px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 14px; font-family: inherit; resize: vertical;
}
.review-image-input { margin-bottom: 14px; font-size: 13px; }
.review-image-preview-row { display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap; }
.review-image-preview-row img { width: 56px; height: 56px; border-radius: 8px; object-fit: cover; }

/* ===== TABLET/DESKTOP — min-width 1024px ===== */
@media (min-width: 1024px) {
    .pdp-layout { flex-direction: row; gap: 56px; padding: 40px 0 80px; }
    .pdp-gallery { flex: 1; max-width: 480px; }
    .pdp-info { flex: 1; }
}
@endsection

@section('content')

<div class="wrap pdp-breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" style="vertical-align:middle;opacity:0.6;"><path d="m9 18 6-6-6-6"></path></svg>
    <a href="{{ route('products.index') }}">Shop</a>
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" style="vertical-align:middle;opacity:0.6;"><path d="m9 18 6-6-6-6"></path></svg>
    <span>{{ $product->name }}</span>
</div>

@if (session('success'))
    <div class="wrap" style="padding-top:16px;">
        <p style="color:var(--color-success, green); font-size:13px;">{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="wrap" style="padding-top:16px;">
        <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="wrap" style="padding-top:16px;">
        @foreach ($errors->all() as $error)
            <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="wrap pdp-layout">

    <div class="pdp-gallery">
        @php $firstImage = $product->images->first(); @endphp
        <div class="pdp-gallery-main" id="pdpMainImage">
            <img src="{{ $firstImage ? asset('storage/' . $firstImage->image) : $product->primary_image_url }}" alt="{{ $product->name }}" id="pdpMainImageTag">
        </div>

        @if ($product->images->count() > 1)
            <div class="pdp-thumbs">
                @foreach ($product->images as $index => $image)
                    <button type="button" class="pdp-thumb-btn {{ $index === 0 ? 'active' : '' }}" data-src="{{ asset('storage/' . $image->image) }}">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $product->name }} image {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="pdp-info">
        <p class="pdp-cat">{{ $product->category->name }}</p>
        <h1>{{ $product->name }}</h1>

        @if ($product->average_rating)
            <div class="review-stars">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                <span style="font-size:13px;opacity:0.7;">{{ number_format($product->average_rating, 1) }} &middot; {{ $product->review_count }} {{ Str::plural('review', $product->review_count) }}</span>
            </div>
        @endif

        <p class="pdp-desc">{{ $product->description ?? 'No description available.' }}</p>

        <form method="POST" action="{{ route('cart.add') }}" class="pdp-add-form" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_variant_id" value="{{ $product->variants->first()->id ?? '' }}" id="selectedVariantId">
            <input type="hidden" name="quantity" value="1">

            <div class="variant-group" role="radiogroup" aria-label="Select weight">
                @foreach ($product->variants as $index => $variant)
                    @php $inStock = $variant->stock_quantity > 0; @endphp
                    <label class="variant-option {{ $index === 0 ? 'selected' : '' }}" data-variant-id="{{ $variant->id }}">
                        <input type="radio" name="variant_display" value="{{ $variant->id }}" @checked($index === 0) {{ !$inStock ? 'disabled' : '' }}>
                        <div>
                            <div class="variant-weight">{{ $variant->weight }}</div>
                            <div class="variant-stock">{{ $inStock ? 'In stock' : 'Out of stock' }}</div>
                        </div>
                        <span class="variant-price">&#8377;{{ number_format($variant->price_minor / 100, 0) }}</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary" @if($product->variants->isEmpty() || $product->variants->every(fn($v) => $v->stock_quantity <= 0)) disabled @endif>
                Add to cart
            </button>
        </form>

        <div class="pdp-secondary-actions">
            <a href="#reviews" class="btn btn-secondary pdp-review-btn" id="topWriteReviewBtn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                Write a Review
            </a>

            @auth
                <button type="button" class="btn btn-secondary pdp-wishlist-btn {{ $inWishlist ? 'active' : '' }}" id="wishlistToggleBtn" data-product-id="{{ $product->id }}" data-url="{{ route('wishlist.toggle', $product->id) }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" id="wishlistHeartIcon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                    <span id="wishlistBtnLabel">{{ $inWishlist ? 'Saved' : 'Save' }}</span>
                </button>
            @else
                                <button type="button" class="btn btn-secondary pdp-wishlist-btn guest-wishlist-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                    Save
                </button>
            @endauth
        </div>
    </div>
</div>

<div class="wrap reviews-section" id="reviews">
    <h2>Reviews</h2>

    @if ($product->review_count > 0)
        <div class="rating-summary">
            <div class="rating-summary-headline">
                <span class="big-number">{{ number_format($product->average_rating, 1) }}</span>
                <span class="out-of">out of 5 &middot; {{ $product->review_count }} {{ Str::plural('review', $product->review_count) }}</span>
            </div>
            <div class="rating-breakdown">
                @foreach ($ratingBreakdown as $star => $data)
                    <div class="rating-bar-row">
                        <span>{{ $star }}&#9733;</span>
                        <div class="rating-bar-track"><div class="rating-bar-fill" style="width:{{ $data['percent'] }}%;"></div></div>
                        <span>{{ $data['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($reviews->isEmpty())
        <div class="reviews-empty-state">
            <p>No reviews yet.</p>
            <p>Be the first to share what you think of this product.</p>
        </div>
    @else
        @foreach ($reviews as $review)
            <div class="review-item" id="review-{{ $review->id }}">
                <div class="review-header-row">
                    <img src="{{ $review->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->reviewer_name) . '&background=b3132d&color=fff' }}" alt="{{ $review->reviewer_name }}" class="review-avatar">
                    <div class="review-name-col">
                        <h4>{{ $review->reviewer_name }}</h4>
                        <div class="review-stars" style="margin-bottom:0;">
                            @for ($i = 0; $i < 5; $i++)
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="{{ $i < $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                            @endfor
                        </div>
                    </div>
                    <span class="review-date">{{ $review->created_at->format('M j, Y') }}</span>
                </div>

                @if ($review->title)
                    <p class="review-title">{{ $review->title }}</p>
                @endif

                <p>{{ $review->review }}</p>

                @if ($review->images->isNotEmpty())
                    <div class="review-images-grid">
                        @foreach ($review->images as $image)
                            <img src="{{ $image->image_url }}" alt="Review image" class="review-image-thumb" onclick="window.open('{{ $image->image_url }}', '_blank')">
                        @endforeach
                    </div>
                @endif

                <div class="helpful-row">
                    @auth
                        <form method="POST" action="{{ route('reviews.helpful', $review->id) }}">
                            @csrf
                            <button type="submit" class="helpful-btn {{ isset($helpfulVoteIds[$review->id]) ? 'active' : '' }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 22V11m0 11h11.28a2 2 0 0 0 2-1.7l1.38-9A2 2 0 0 0 19.66 10H14V4a2 2 0 0 0-2-2 1 1 0 0 0-1 1v.5a5.5 5.5 0 0 1-1.5 3.78L7 9.5"></path></svg>
                                Helpful @if($review->helpful_votes_count ?? 0) ({{ $review->helpful_votes_count }}) @endif
                            </button>
                        </form>
                    @else
                        <span style="font-size:12px;opacity:0.5;">{{ $review->helpful_votes_count ?? 0 }} found this helpful</span>
                    @endauth
                </div>

                @if ($review->reply)
                    <div class="seller-reply">
                        <span class="seller-reply-label">Reply from Rasova</span>
                        <p>{{ $review->reply->reply }}</p>
                    </div>
                @endif
            </div>
        @endforeach

        @if ($reviews->hasPages())
            <div class="review-pagination">
                @for ($page = 1; $page <= $reviews->lastPage(); $page++)
                    <a href="{{ $reviews->url($page) }}#reviews" class="{{ $page === $reviews->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endfor
            </div>
        @endif
    @endif

    <div class="write-review-block">
        <div class="review-form-collapsible" id="reviewFormWrapper">
            <div class="review-form-inner">
                <h3>{{ $userReview ? 'Update your review' : 'Write a Review' }}</h3>
                <form method="POST" action="{{ route('reviews.store', $product->id) }}" id="reviewForm">
                    @csrf
                    <input type="hidden" name="rating" id="reviewRatingInput" value="{{ $userReview->rating ?? '' }}">

                    @guest
                        <input type="text" name="guest_name" class="review-title-input" placeholder="Your name" value="{{ old('guest_name') }}" required maxlength="150">
                    @endguest

                    <label class="review-anon-check">
                        <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous', $userReview->is_anonymous ?? false) ? 'checked' : '' }}>
                        Post as Anonymous
                    </label>

                    @guest
                        <input type="email" name="guest_email" class="review-title-input" placeholder="Your email (optional)" value="{{ old('guest_email') }}" maxlength="255">
                    @endguest

                    <div class="review-star-picker" id="reviewStarPicker">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="review-star-btn {{ ($userReview->rating ?? 0) >= $i ? 'filled' : '' }}" data-value="{{ $i }}" aria-label="Rate {{ $i }} stars">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                            </button>
                        @endfor
                    </div>

                    <textarea name="review" class="review-textarea" placeholder="What did you think of this product?" required>{{ old('review', $userReview->review ?? '') }}</textarea>

                    <div class="review-form-actions">
                        <button type="submit" class="btn btn-primary" style="min-height:44px;padding:0 24px;">Submit Review</button>
                        <button type="button" class="btn btn-secondary" id="cancelReviewFormBtn" style="min-height:44px;padding:0 24px;">Cancel</button>
                    </div>
                    @if ($userReview && $userReview->status === 'pending')
                        <p style="font-size:12px;opacity:0.6;margin-top:8px;">Your review is awaiting approval.</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        var picker = document.getElementById('reviewStarPicker');
        if (!picker) return;
        var buttons = picker.querySelectorAll('.review-star-btn');
        var input = document.getElementById('reviewRatingInput');

        function highlight(value) {
            buttons.forEach(function (btn) {
                btn.classList.toggle('filled', parseInt(btn.dataset.value, 10) <= value);
            });
        }

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                input.value = btn.dataset.value;
                highlight(parseInt(btn.dataset.value, 10));
            });
            btn.addEventListener('mouseenter', function () {
                highlight(parseInt(btn.dataset.value, 10));
            });
        });

        picker.addEventListener('mouseleave', function () {
            highlight(parseInt(input.value || 0, 10));
        });

        document.getElementById('reviewForm').addEventListener('submit', function (e) {
            if (!input.value) {
                e.preventDefault();
                alert('Please select a star rating.');
            }
        });

        // Collapsible form toggle — triggered by the "Write a Review" button near Add to Cart
        var topBtn = document.getElementById('topWriteReviewBtn');
        var wrapper = document.getElementById('reviewFormWrapper');
        var cancelBtn = document.getElementById('cancelReviewFormBtn');
        var form = document.getElementById('reviewForm');

        function openForm() {
            wrapper.classList.add('open');
            wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
        }

        function closeForm() {
            wrapper.style.maxHeight = '0px';
            wrapper.classList.remove('open');
            form.reset();
            input.value = '';
            highlight(0);
        }

        topBtn && topBtn.addEventListener('click', function (e) {
            // Let the native #reviews anchor scroll happen, then expand the form.
            openForm();
        });
        cancelBtn.addEventListener('click', closeForm);
    })();

    // Thumbnail gallery swap
    (function () {
        var thumbBtns = document.querySelectorAll('.pdp-thumb-btn');
        var mainImg = document.getElementById('pdpMainImageTag');
        thumbBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                mainImg.src = btn.getAttribute('data-src');
                thumbBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
            });
        });
    })();

    // Variant selection
    (function () {
        var variantOptions = document.querySelectorAll('.variant-option');
        var hiddenInput = document.getElementById('selectedVariantId');
        variantOptions.forEach(function (opt) {
            opt.addEventListener('click', function () {
                var radio = opt.querySelector('input[type="radio"]');
                if (radio.disabled) return;
                variantOptions.forEach(function (o) { o.classList.remove('selected'); });
                opt.classList.add('selected');
                radio.checked = true;
                hiddenInput.value = opt.getAttribute('data-variant-id');
            });
        });
    })();

    // Wishlist toggle
    (function () {
        var btn = document.getElementById('wishlistToggleBtn');
        if (!btn) return;

        var heart = document.getElementById('wishlistHeartIcon');
        var label = document.getElementById('wishlistBtnLabel');

        btn.addEventListener('click', function () {
            fetch(btn.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    btn.classList.toggle('active', data.in_wishlist);
                    heart.setAttribute('fill', data.in_wishlist ? 'currentColor' : 'none');
                    label.textContent = data.in_wishlist ? 'Saved' : 'Save';
                })
                .catch(function () {
                    alert('Something went wrong — please try again.');
                });
        });
    })();
</script>
@endpush