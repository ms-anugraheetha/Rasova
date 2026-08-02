@extends('layouts.storefront')

@section('title', ($term ? 'Search: ' . $term : 'Search') . ' — Rasova')

@section('extra-styles')
.search-header { padding: 28px 0 20px; }
.search-header h1 { font-size: clamp(22px, 6vw, 32px); margin: 0 0 6px; }
.search-header p { opacity: 0.65; margin: 0; font-size: 14px; }

.search-results-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; padding-bottom: 56px; }
.search-pcard { display: flex; flex-direction: column; gap: 8px; text-decoration: none; color: inherit; }
.search-pcard-img { aspect-ratio: 1; border-radius: 16px; overflow: hidden; background: var(--color-surface); }
.search-pcard-img img { width: 100%; height: 100%; object-fit: cover; }
.search-pcard-cat { font-size: 11px; opacity: 0.55; text-transform: uppercase; letter-spacing: 0.03em; }
.search-pcard h3 { font-size: 15px; margin: 0; }
.search-pcard-price { font-weight: 700; font-size: 14px; }

.search-empty { padding: 56px 20px; text-align: center; }
.search-empty p { opacity: 0.7; margin-bottom: 20px; font-size: 15px; }

@media (min-width: 768px) {
    .search-results-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
}
@media (min-width: 1024px) {
    .search-results-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; }
}
@endsection

@section('content')

<header class="wrap search-header">
    <h1>{{ $term ? 'Search results for "' . $term . '"' : 'Search' }}</h1>
    @if ($term)
        <p>{{ $products->total() }} {{ Str::plural('result', $products->total()) }} found</p>
    @endif
</header>

<div class="wrap">
    @if ($products->isEmpty())
        <div class="search-empty">
            <p>No products found.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="search-results-grid">
            @foreach ($products as $product)
                @php $variant = $product->default_variant; @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="search-pcard">
                    <div class="search-pcard-img">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                    </div>
                    @if ($product->category)
                        <span class="search-pcard-cat">{{ $product->category->name }}</span>
                    @endif
                    <h3>{{ $product->name }}</h3>
                    @if ($variant)
                        <span class="search-pcard-price">&#8377;{{ number_format($variant->price_minor / 100, 0) }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        @if ($products->hasPages())
            <div style="display:flex;justify-content:center;gap:6px;margin-bottom:56px;">
                {{ $products->links() }}
            </div>
        @endif
    @endif
</div>

@endsection