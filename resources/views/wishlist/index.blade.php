@extends('layouts.storefront')

@section('title', 'Your Wishlist — Rasova')

@section('extra-styles')
.wishlist-header { padding: 28px 0 20px; }
.wishlist-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }
.wishlist-layout { padding-bottom: 64px; }
.wishlist-empty { padding: 48px 0; text-align: center; }
.wishlist-empty p { opacity: 0.7; margin-bottom: 18px; }
.wishlist-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
.wishlist-card { display: flex; flex-direction: column; gap: 6px; position: relative; }
.wishlist-img { position: relative; aspect-ratio: 1; border-radius: 16px; overflow: hidden; background: var(--color-surface); display: block; }
.wishlist-img img { width: 100%; height: 100%; object-fit: cover; }
.wishlist-remove-btn {
    position: absolute; top: 10px; right: 10px; width: 36px; height: 36px; border-radius: 50%;
    background: var(--color-bg); border: none; display: grid; place-items: center; cursor: pointer;
    color: var(--color-accent); box-shadow: var(--shadow-sm); z-index: 2;
}
.wishlist-stars { display: inline-flex; gap: 2px; align-items: center; color: var(--color-star); }
.wishlist-stock-row { display: flex; align-items: center; gap: 6px; font-size: 12px; opacity: 0.7; }
.wishlist-stock-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
.wishlist-add-btn { min-height: 40px; font-size: 13px; margin-top: 4px; }

@media (min-width: 768px) {
    .wishlist-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
}
@media (min-width: 1024px) {
    .wishlist-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; }
}
@endsection

@section('content')

<header class="wrap wishlist-header">
    <h1>Your Wishlist</h1>
</header>

<div class="wrap wishlist-layout">
    @if ($products->isEmpty())
        <div class="wishlist-empty">
            <p>Your wishlist is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="wishlist-grid">
            @foreach ($products as $product)
                @php
                    $variant = $product->default_variant;
                    $inStock = $variant && $variant->stock_quantity > 0;
                @endphp
                <div class="wishlist-card">
                    <a href="{{ route('products.show', $product->slug) }}" class="wishlist-img">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                    </a>
                    <button type="button" class="wishlist-remove-btn" data-url="{{ route('wishlist.toggle', $product->id) }}" title="Remove from wishlist">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                    </button>

                    <a href="{{ route('products.show', $product->slug) }}" style="text-decoration:none;color:inherit;">
                        <h3 style="font-size:15px;margin:0;">{{ $product->name }}</h3>
                    </a>

                    @if ($product->average_rating)
                        <div class="wishlist-stars">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                            <span style="font-size:12px;opacity:0.7;">{{ number_format($product->average_rating, 1) }} ({{ $product->review_count }})</span>
                        </div>
                    @endif

                    @if ($variant)
                        <span style="font-weight:700;font-size:14px;">&#8377;{{ number_format($variant->price_minor / 100, 0) }}</span>

                        <div class="wishlist-stock-row">
                            <span class="wishlist-stock-dot" style="background:{{ $inStock ? 'var(--color-success)' : 'var(--color-neutral-500)' }};"></span>
                            {{ $inStock ? 'In stock' : 'Out of stock' }}
                        </div>

                        @if ($inStock)
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary wishlist-add-btn" style="width:100%;">Add to Cart</button>
                            </form>
                        @else
                            <button class="btn btn-secondary wishlist-add-btn" style="width:100%;" disabled>Out of stock</button>
                        @endif
                    @else
                        <span style="font-size:12px;opacity:0.6;">Currently unavailable</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.wishlist-remove-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            fetch(btn.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
                .then(function () { btn.closest('.wishlist-card').remove(); })
                .catch(function () { alert('Something went wrong — please try again.'); });
        });
    });
</script>
@endpush