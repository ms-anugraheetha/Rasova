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

.reviews-section { padding-top: 40px; border-top: 1px solid var(--color-divider); margin-top: 8px; }
.reviews-section h2 { font-size: clamp(20px, 5vw, 26px); margin: 0 0 20px; }
.review-item { padding: 16px 0; border-bottom: 1px solid var(--color-divider); }
.review-item:last-child { border-bottom: none; }
.review-stars { display: inline-flex; gap: 2px; color: var(--color-star); margin-bottom: 6px; }
.review-item p { margin: 0; font-size: 14px; opacity: 0.8; }

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
                <span style="font-size:13px;opacity:0.7;">{{ number_format($product->average_rating, 1) }}</span>
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

            @auth
                <button type="submit" class="btn btn-primary" @if($product->variants->isEmpty() || $product->variants->every(fn($v) => $v->stock_quantity <= 0)) disabled @endif>
                    Add to cart
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="display:block;text-align:center;">Log in to buy</a>
            @endauth
        </form>
    </div>
</div>

@if ($product->reviews->isNotEmpty())
    <div class="wrap reviews-section">
        <h2>Reviews</h2>
        @foreach ($product->reviews as $review)
            <div class="review-item">
                <div class="review-stars">
                    @for ($i = 0; $i < 5; $i++)
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i < $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                    @endfor
                </div>
                <p>{{ $review->review }}</p>
            </div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
<script>
    (function () {
        // Thumbnail gallery swap
        var thumbBtns = document.querySelectorAll('.pdp-thumb-btn');
        var mainImg = document.getElementById('pdpMainImageTag');
        thumbBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                mainImg.src = btn.getAttribute('data-src');
                thumbBtns.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
            });
        });

        // Variant selection
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
</script>
@endpush