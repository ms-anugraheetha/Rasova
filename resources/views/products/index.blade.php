@extends('layouts.storefront')

@section('title', 'Shop All Pickles — Rasova')

@section('extra-styles')
/* ===== MOBILE BASE ===== */
.pcard { display: flex; flex-direction: column; gap: 8px; }
.pcard-img { position: relative; aspect-ratio: 1; border-radius: 16px; overflow: hidden; background: var(--color-surface); }
.pcard-img img { width: 100%; height: 100%; object-fit: cover; }
.pcard-wish {
    position: absolute; top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 50%;
    background: var(--color-bg); display: grid; place-items: center; box-shadow: var(--shadow-sm); cursor: pointer;
    border: none; padding: 0; color: var(--color-text); text-decoration: none;
}
.pcard-wish.active { color: var(--color-accent); }
.stars { display: inline-flex; gap: 2px; align-items: center; color: var(--color-star); }
.stock-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
.filter-group { padding: 16px 0; border-bottom: 1px solid var(--color-divider); }
.filter-group:first-child { padding-top: 0; }
.filter-group .radio { display: flex; margin-bottom: 12px; min-height: 24px; }

.breadcrumb { font-size: 13px; opacity: 0.6; padding: 16px 0 0; }
.plist-header { padding: 14px 0 22px; }
.plist-header h1 { font-size: clamp(26px, 6vw, 36px); margin: 0 0 6px; }

.plist-layout { padding-bottom: 56px; }
.plist-toolbar { display: flex; flex-direction: column; align-items: stretch; gap: 12px; margin-bottom: 16px; }
.plist-toolbar-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
.sort-form { display: flex; align-items: center; }
.filter-toggle-btn { min-height: 44px; }
.seg { width: 100%; }
.seg-opt { flex: 1; justify-content: center; min-height: 44px; }


/* Filter drawer: bottom sheet on mobile/tablet, becomes static sidebar at desktop */
.plist-sidebar {
    display: none;
}
.plist-sidebar.open {
    display: block;
    position: fixed; inset: auto 0 0 0; z-index: 70;
    background: var(--color-bg); border-radius: 20px 20px 0 0;
    padding: 20px 20px calc(20px + env(safe-area-inset-bottom));
    max-height: 82vh; overflow-y: auto; box-shadow: var(--shadow-lg);
}
.filter-scrim { display: none; }
.filter-scrim.show { display: block; position: fixed; inset: 0; background: color-mix(in srgb, #000 40%, transparent); z-index: 65; }
.filter-drawer-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.filter-apply-btn { width: 100%; min-height: 48px; margin-top: 8px; }

.plist-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
.pcard .btn-block { min-height: 44px; }

.pagination-row { display: flex; justify-content: center; gap: 6px; margin-top: 36px; flex-wrap: wrap; }
.pagination-row .btn-icon { width: 44px; height: 44px; }

/* ===== TABLET — min-width 768px ===== */
@media (min-width: 768px) {
    .plist-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .plist-toolbar { flex-direction: row; justify-content: space-between; align-items: center; }
    .seg { width: auto; }
}

/* ===== DESKTOP — min-width 1024px ===== */
@media (min-width: 1024px) {
    .plist-layout { display: grid; grid-template-columns: 220px 1fr; gap: 40px; padding-bottom: 80px; }
    .filter-toggle-btn { display: none; }
    .plist-sidebar, .plist-sidebar.open {
        display: block; position: static; background: none; box-shadow: none;
        padding: 0; max-height: none; border-radius: 0; overflow: visible;
    }
    .filter-drawer-head, .filter-apply-btn { display: none; }
    .filter-scrim.show { display: none; }
    .plist-grid { grid-template-columns: repeat(3, 1fr); gap: 24px; }
}
@media (min-width: 1280px) {
    .plist-grid { grid-template-columns: repeat(4, 1fr); gap: 28px; }
}
@endsection

@section('content')

<div class="wrap breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" style="vertical-align:middle;opacity:0.6;"><path d="m9 18 6-6-6-6"></path></svg>
    <span>Shop</span>
</div>

@if ($errors->any())
    <div class="wrap" style="padding-top:12px;">
        @foreach ($errors->all() as $error)
            <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<header class="wrap plist-header">
    <h1>All pickles</h1>
    <p style="opacity:0.7;margin:0;">{{ $products->total() }} handmade varieties.</p>
</header>

<div class="wrap plist-layout">

    <div class="filter-scrim" id="filterScrim"></div>

    <aside class="plist-sidebar" id="filterSidebar">
        <div class="filter-drawer-head">
            <h6 style="margin:0;">Filters</h6>
            <button type="button" class="icon-btn" id="filterCloseBtn" aria-label="Close filters">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </button>
        </div>

        <form method="GET" action="{{ route('products.index') }}" id="filterForm">
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="filter-group">
                <h6 style="margin-bottom:14px;">Category</h6>
                <label class="radio"><input type="radio" name="category" value="" onchange="this.form.submit()" @checked(!request('category'))><span class="dot"></span>All</label>
                @foreach($categories as $category)
                    <label class="radio"><input type="radio" name="category" value="{{ $category->slug }}" onchange="this.form.submit()" @checked(request('category') === $category->slug)><span class="dot"></span>{{ $category->name }}</label>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary filter-apply-btn">Show results</button>
        </form>
    </aside>

    <div>
        <div class="plist-toolbar">
            <div class="plist-toolbar-row">
                <span style="font-size:13px;opacity:0.65;">Showing {{ $products->firstItem() ?? 0 }}&ndash;{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
                <button type="button" class="btn btn-secondary filter-toggle-btn" id="filterOpenBtn">
                    Filters
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M4 6h16M7 12h10M10 18h4"></path></svg>
                </button>
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="sort-form">
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <label for="sortSelect" style="font-size:13px;opacity:0.65;margin-right:8px;">Sort by</label>
                <select name="sort" id="sortSelect" class="admin-select" onchange="this.form.submit()" style="min-height:44px;border-radius:10px;border:1px solid var(--color-divider);background:var(--color-bg);color:inherit;padding:0 12px;">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                    <option value="name_asc" @selected(request('sort') === 'name_asc')>Name: A to Z</option>
                </select>
            </form>
        </div>

        <div class="plist-grid">
            @forelse($products as $product)
                @php
                    $variant = $product->default_variant;
                    $inStock = $variant && $variant->stock_quantity > 0;
                    $lowStock = $inStock && $variant->stock_quantity <= 5;
                @endphp
                <div class="pcard">
                    <div class="pcard-img">
                        <a href="{{ route('products.show', $product->slug) }}" style="display:block;width:100%;height:100%;">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                        </a>
                        @auth
                            <button type="button" class="pcard-wish {{ ($wishlistedProductIds ?? collect())->has($product->id) ? 'active' : '' }}" data-url="{{ route('wishlist.toggle', $product->id) }}" title="Save to wishlist">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ ($wishlistedProductIds ?? collect())->has($product->id) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                            </button>
                        @else
                            <button type="button" class="pcard-wish guest-wishlist-btn" title="Log in to save">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                            </button>
                        @endauth
                    </div>

                    @if($product->average_rating)
                        <div class="stars">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                            <span style="font-size:12px;color:var(--color-text);opacity:0.7;">{{ number_format($product->average_rating, 1) }} ({{ $product->review_count }})</span>
                        </div>
                    @endif

                    <a href="{{ route('products.show', $product->slug) }}" style="color:inherit;text-decoration:none;">
                        <h3 style="font-size:15px;margin:0;">{{ $product->name }}</h3>
                    </a>

                    @if($variant)
                        <div style="display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;">
                            <span style="font-weight:700;">&#8377;{{ number_format($variant->price_minor / 100, 0) }}</span>
                        </div>

                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;opacity:0.7;">
                            <span class="stock-dot" style="background:{{ !$inStock ? 'var(--color-neutral-500)' : ($lowStock ? 'var(--color-warning)' : 'var(--color-success)') }};"></span>
                            {{ !$inStock ? 'Out of stock' : ($lowStock ? 'Only ' . $variant->stock_quantity . ' left' : 'In stock') }}
                        </div>

                        @if($inStock)
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block">Add to cart</button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-block" disabled>Notify me</button>
                        @endif
                    @else
                        <div style="font-size:12px;opacity:0.6;">Currently unavailable</div>
                        <button class="btn btn-secondary btn-block" disabled>Unavailable</button>
                    @endif
                </div>
            @empty
                <p style="grid-column:1/-1;opacity:0.7;padding:32px 0;">No pickles match those filters yet -try clearing a filter.</p>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="pagination-row">
                @if($products->onFirstPage())
                    <button class="btn btn-secondary btn-icon" disabled aria-label="Previous">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="m15 18-6-6 6-6"></path></svg>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="btn btn-secondary btn-icon" aria-label="Previous">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="m15 18-6-6 6-6"></path></svg>
                    </a>
                @endif

                @for($page = 1; $page <= $products->lastPage(); $page++)
                    <a href="{{ $products->url($page) }}" class="btn {{ $page === $products->currentPage() ? 'btn-primary' : 'btn-secondary' }} btn-icon">{{ $page }}</a>
                @endfor

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="btn btn-secondary btn-icon" aria-label="Next">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="m9 18 6-6-6-6"></path></svg>
                    </a>
                @else
                    <button class="btn btn-secondary btn-icon" disabled aria-label="Next">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="m9 18 6-6-6-6"></path></svg>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        var openBtn = document.getElementById('filterOpenBtn');
        var closeBtn = document.getElementById('filterCloseBtn');
        var sidebar = document.getElementById('filterSidebar');
        var scrim = document.getElementById('filterScrim');
        function open() { sidebar.classList.add('open'); scrim.classList.add('show'); }
        function close() { sidebar.classList.remove('open'); scrim.classList.remove('show'); }
        openBtn && openBtn.addEventListener('click', open);
        closeBtn && closeBtn.addEventListener('click', close);
        scrim && scrim.addEventListener('click', close);
    })();

    // Wishlist toggle (product cards)
    document.querySelectorAll('button.pcard-wish[data-url]').forEach(function (btn) {
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
                    btn.querySelector('svg').setAttribute('fill', data.in_wishlist ? 'currentColor' : 'none');
                })
                .catch(function () {
                    alert('Something went wrong — please try again.');
                });
        });
    });
</script>
@endpush