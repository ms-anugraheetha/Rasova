@extends('layouts.storefront')

@section('title', 'Rasova — Homemade Kerala Pickles, Made Slow')

@section('extra-styles')
/* ===== MOBILE BASE ===== */
.pcard { display: flex; flex-direction: column; gap: 8px; }
.pcard-img { position: relative; aspect-ratio: 1; border-radius: 18px; overflow: hidden; background: var(--color-surface); }
.pcard-img img { width: 100%; height: 100%; object-fit: cover; }
.pcard-wish {
    position: absolute; top: 10px; right: 10px; width: 40px; height: 40px; border-radius: 50%;
    background: var(--color-bg); display: grid; place-items: center; box-shadow: var(--shadow-sm);
    cursor: pointer; color: var(--color-text); border: none; padding: 0; text-decoration: none;
}
.pcard-wish:hover { color: var(--color-accent); }
.pcard-wish.active { color: var(--color-accent); }
.stars { display: inline-flex; gap: 2px; color: var(--color-star); align-items: center; }
.cat-circle { aspect-ratio: 1; border-radius: 50%; overflow: hidden; background: var(--color-accent-2-100); transition: transform 0.2s ease, box-shadow 0.2s ease; }
.cat-circle img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.cat-card { text-align: center; text-decoration: none; color: inherit; display: block; }
.cat-card:hover .cat-circle { transform: translateY(-4px); box-shadow: var(--shadow-sm); }
.cat-card:hover .cat-circle img { transform: scale(1.08); }

.hero-section { position: relative; padding: 0 0 48px; }
.hero-banner { width: 100%; aspect-ratio: 1/1; overflow: hidden; }
.hero-banner img { width: 100%; height: 100%; object-fit: cover; display: block; }
.hero-copy { text-align: center; padding-top: 28px; }
.hero-copy h1 { font-size: clamp(32px, 8vw, 60px); line-height: 1.08; margin: 16px 0; }
.hero-copy p { font-size: clamp(15px, 3.6vw, 17px); opacity: 0.78; margin: 0 auto 22px; max-width: 46ch; }
.hero-actions { display: flex; flex-direction: column; align-items: center; gap: 12px; }
.hero-actions .btn { width: 100%; max-width: 320px; min-height: 48px; font-size: 15px; }

.section-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 18px; }
.section-head h2 { font-size: clamp(22px, 5vw, 30px); margin: 0; }
.section-head a { font-size: 14px; white-space: nowrap; }
.bestseller-placeholder-note { font-size: 13px; opacity: 0.6; margin: -8px 0 20px; max-width: 56ch; }

.cat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.best-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }

.story-section { padding-bottom: 56px; }
.story-grid { display: grid; grid-template-columns: 1fr; gap: 28px; align-items: center; }
.story-grid figure { border-radius: 24px; overflow: hidden; margin: 0; }
.story-grid h2 { font-size: clamp(24px, 6vw, 34px); margin: 14px 0; }
.story-grid p { font-size: 15px; opacity: 0.78; max-width: 52ch; margin-bottom: 12px; }
.story-stats { display: flex; gap: 24px; margin-top: 20px; flex-wrap: wrap; }
.story-stats p:first-child { font-size: 24px; margin: 0; font-family: var(--font-heading); }
.story-stats p:last-child { font-size: 11px; opacity: 0.6; margin: 4px 0 0; }

.quote-section { padding-bottom: 56px; }
.quote-section blockquote { font-family: var(--font-heading); font-size: clamp(20px, 5vw, 27px); line-height: 1.45; max-width: 32ch; margin: 0; }
.testimonial-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
.testimonial-card {
    display: block; text-decoration: none; color: inherit; padding: 20px; border-radius: 16px;
    background: var(--color-surface); transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.testimonial-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }
.testimonial-head { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.testimonial-name { font-size: 14px; font-weight: 600; margin: 0 0 2px; }
.testimonial-excerpt { font-size: 14px; opacity: 0.8; line-height: 1.5; margin: 0 0 10px !important; }
.testimonial-product { font-size: 12px; opacity: 0.55; margin: 0 !important; }
.verified-badge { font-size: 10px; background: var(--color-accent-2-100); padding: 1px 7px; border-radius: 6px; margin-left: 4px; }

.reviews-empty-state { text-align: center; padding: 48px 20px; background: var(--color-surface); border-radius: 20px; }
.reviews-empty-icon { color: var(--color-accent-700); opacity: 0.5; margin-bottom: 16px; }
.reviews-empty-state h3 { font-size: 18px; margin: 0 0 8px; }
.reviews-empty-state p { font-size: 14px; opacity: 0.68; max-width: 44ch; margin: 0 auto; line-height: 1.6; }

.about-cta-section { padding: 64px 0; text-align: center; }
.about-cta-section h2 { font-size: clamp(26px, 6vw, 38px); margin: 0 0 14px; }
.about-cta-section p { font-size: 15px; opacity: 0.72; max-width: 46ch; margin: 0 auto 28px; }
.about-cta-actions { display: flex; flex-direction: column; gap: 12px; max-width: 320px; margin: 0 auto; }
.about-cta-actions .btn { min-height: 48px; }

.newsletter-section { background: var(--color-accent-2-100); padding: 40px 0; }
.newsletter-row { display: flex; flex-direction: column; gap: 20px; }
.newsletter-row h3 { font-size: clamp(19px, 4.5vw, 25px); margin: 0 0 6px; }
.newsletter-form { display: flex; flex-direction: column; gap: 10px; }
.newsletter-form input, .newsletter-form button { min-height: 48px; }

/* ===== TABLET — min-width 768px ===== */
@media (min-width: 768px) {
    .hero-section { padding: 0 0 64px; }
    .hero-banner { aspect-ratio: 16/9; }
    .hero-copy { padding-top: 40px; }
    .hero-actions { flex-direction: row; justify-content: center; }
    .hero-actions .btn { width: auto; }
    .cat-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .best-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .story-grid { grid-template-columns: 0.9fr 1.1fr; gap: 40px; }
    .testimonial-grid { grid-template-columns: repeat(2, 1fr); }
    .newsletter-row { flex-direction: row; justify-content: space-between; align-items: center; }
    .newsletter-form { flex-direction: row; max-width: 420px; flex: 1; min-width: 280px; }
    .about-cta-actions { flex-direction: row; max-width: none; justify-content: center; }
    .about-cta-actions .btn { min-width: 180px; }
}

/* ===== DESKTOP — min-width 1024px ===== */
@media (min-width: 1024px) {
    .hero-section { padding: 0 0 88px; }
    .hero-banner { aspect-ratio: 21/9; }
    .hero-copy { padding-top: 48px; }
    .best-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .testimonial-grid { grid-template-columns: repeat(3, 1fr); }
    .story-section, .quote-section { padding-bottom: 88px; }
    .newsletter-section { padding: 56px 0; }
}
@endsection

@section('content')

<section class="hero-section">
    <figure class="hero-banner" style="margin:0;">
        <img src="{{ asset('design/hero-jar-1.png') }}" alt="Jars of Rasova pickle on a warm surface">
    </figure>

    <div class="wrap hero-copy">
        <span class="tag tag-accent">Kerala, since 2023</span>
        <h1>Homemade Kerala pickles, Made Fresh with love.</h1>
        <p>Prepared fresh after every order, with no preservatives and no shortcuts.</p>
        <div class="hero-actions">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Shop the collection
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('about') }}" class="btn btn-ghost">Our story</a>
        </div>
    </div>
</section>

<section class="wrap" style="padding-bottom:48px;">
    <div class="section-head">
        <h2>Shop by variety</h2>
        <a href="{{ route('products.index') }}">View all </a>
    </div>
    <div class="cat-grid">
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="cat-card">
                <div class="cat-circle">
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                </div>
                <p style="margin:10px 0 0;font-weight:600;font-size:14px;">{{ $category->name }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="wrap" style="padding-bottom:56px;">
    <div class="section-head">
        <h2>Best Sellers</h2>
        <a href="{{ route('products.index') }}">View all </a>
    </div>
    @if ($showBestsellerPlaceholder ?? false)
        <p class="bestseller-placeholder-note">Our best sellers will appear here as customers start placing orders. Until then, discover our handcrafted homemade Kerala pickles.</p>
    @endif
    <div class="best-grid">
        @foreach($bestsellers as $product)
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

                <a href="{{ route('products.show', $product->slug) }}" style="text-decoration:none;color:inherit;">
                    @if($product->average_rating)
                        <div class="stars">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                            <span style="font-size:12px;color:var(--color-text);opacity:0.7;">{{ number_format($product->average_rating, 1) }} ({{ $product->review_count }})</span>
                        </div>
                    @endif

                    <h3 style="font-size:15px;margin:0;">{{ $product->name }}</h3>

                    @if($product->default_variant)
                        <div style="display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;">
                            <span style="font-weight:700;">&#8377;{{ number_format($product->default_variant->price_minor / 100, 0) }}</span>
                        </div>
                    @endif
                </a>
            </div>
        @endforeach
    </div>
</section>

<section class="wrap quote-section" id="reviews">
    <div class="section-head">
        <h2>Customer Reviews</h2>
        <a href="{{ route('products.index') }}">View all reviews </a>
    </div>

    @if ($testimonials->isNotEmpty())
        <div class="testimonial-grid">
            @foreach ($testimonials as $review)
                <a href="{{ route('products.show', $review->product->slug) }}#review-{{ $review->id }}" class="testimonial-card">
                    <div class="testimonial-head">
                        <img src="{{ $review->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->reviewer_name) . '&background=b3132d&color=fff' }}" alt="{{ $review->reviewer_name }}" class="testimonial-avatar">
                        <div>
                            <p class="testimonial-name">{{ $review->reviewer_name }}</p>
                            <div class="review-stars" style="margin-bottom:0;">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="{{ $i < $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="testimonial-excerpt">&ldquo;{{ \Illuminate\Support\Str::limit($review->review, 140) }}&rdquo;</p>
                    <p class="testimonial-product">{{ $review->product->name }}</p>
                </a>
            @endforeach
        </div>
    @else
        <div class="reviews-empty-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="reviews-empty-icon">
                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
            </svg>
            <h3>Customer Reviews</h3>
            <p>We're excited to hear what you think! Our first customer reviews will appear here once orders are delivered and customers share their experiences.</p>
        </div>
    @endif
</section>

<section class="about-cta-section">
    <div class="wrap">
        <h2>Bring Kerala Home.</h2>
        <p>Experience authentic homemade Kerala pickles prepared fresh for every order.</p>
        <div class="about-cta-actions">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Collection</a>
            <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Us</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
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