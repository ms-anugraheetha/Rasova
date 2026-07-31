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
    cursor: pointer; color: var(--color-text);
}
.pcard-wish:hover { color: var(--color-accent); }
.stars { display: inline-flex; gap: 2px; color: var(--color-star); align-items: center; }
.cat-circle { aspect-ratio: 1; border-radius: 50%; overflow: hidden; background: var(--color-accent-2-100); }
.cat-circle img { width: 100%; height: 100%; object-fit: cover; }

.hero-section { position: relative; padding: 28px 0 48px; }
.hero-grid { display: flex; flex-direction: column; gap: 28px; }
.hero-copy h1 { font-size: clamp(32px, 8vw, 60px); line-height: 1.08; margin: 16px 0; }
.hero-copy p { font-size: clamp(15px, 3.6vw, 17px); opacity: 0.78; margin-bottom: 22px; max-width: 46ch; }
.hero-actions { display: flex; flex-direction: column; gap: 12px; }
.hero-actions .btn { width: 100%; min-height: 48px; font-size: 15px; }

.section-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 18px; }
.section-head h2 { font-size: clamp(22px, 5vw, 30px); margin: 0; }
.section-head a { font-size: 14px; white-space: nowrap; }

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

.newsletter-section { background: var(--color-accent-2-100); padding: 40px 0; }
.newsletter-row { display: flex; flex-direction: column; gap: 20px; }
.newsletter-row h3 { font-size: clamp(19px, 4.5vw, 25px); margin: 0 0 6px; }
.newsletter-form { display: flex; flex-direction: column; gap: 10px; }
.newsletter-form input, .newsletter-form button { min-height: 48px; }

/* ===== TABLET — min-width 768px ===== */
@media (min-width: 768px) {
    .hero-section { padding: 40px 0 64px; }
    .hero-actions { flex-direction: row; }
    .hero-actions .btn { width: auto; }
    .cat-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .best-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .story-grid { grid-template-columns: 0.9fr 1.1fr; gap: 40px; }
    .newsletter-row { flex-direction: row; justify-content: space-between; align-items: center; }
    .newsletter-form { flex-direction: row; max-width: 420px; flex: 1; min-width: 280px; }
}

/* ===== DESKTOP — min-width 1024px ===== */
@media (min-width: 1024px) {
    .hero-section { padding: 56px 0 88px; }
    .hero-grid { flex-direction: row; align-items: center; gap: 56px; }
    .hero-copy, .hero-figure { flex: 1; }
    .best-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .story-section, .quote-section { padding-bottom: 88px; }
    .newsletter-section { padding: 56px 0; }
}
@endsection

@section('content')

<section class="wrap hero-section">
    <div style="position:absolute;right:-160px;top:-80px;width:420px;height:420px;border-radius:50%;background:var(--color-accent-2-200);z-index:-1;opacity:0.7;"></div>
    <div class="hero-grid">
        <div class="hero-copy">
            <span class="tag tag-accent"> Kerala, since 2023</span>
            <h1>Homemade Kerala pickles, Made Fresh with love.</h1>
            <p> Prepared fresh after every order, with no preservatives and no shortcuts.</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Shop the collection
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
                <a href="#story" class="btn btn-ghost">Our story</a>
            </div>
        </div>
        <figure class="washed hero-figure" style="margin:0;border-radius:24px;overflow:hidden;">
            <img src="{{ asset('design/hero-jar.png') }}" alt="Jars of Rasova pickle on a warm surface" style="width:100%;aspect-ratio:4/5;object-fit:cover;">
        </figure>
    </div>
</section>

<section class="wrap" style="padding-bottom:48px;">
    <div class="section-head">
        <h2>Shop by variety</h2>
        <a href="{{ route('products.index') }}">View all </a>
    </div>
    <div class="cat-grid">
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" style="text-align:center;text-decoration:none;color:inherit;">
                <div class="cat-circle">
                    <img src="{{ $category->image_url ?? asset('design/placeholder-category.jpg') }}" alt="{{ $category->name }}">
                </div>
                <p style="margin:10px 0 0;font-weight:600;font-size:14px;">{{ $category->name }}</p>
            </a>
        @endforeach
    </div>
</section>

<section class="wrap" style="padding-bottom:56px;">
    <div class="section-head">
        <h2>Bestsellers</h2>
        <a href="{{ route('products.index') }}">View all </a>
    </div>
    <div class="best-grid">
        @foreach($bestsellers as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="pcard" style="text-decoration:none;color:inherit;">
                <div class="pcard-img">
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                    <div class="pcard-wish">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>
                    </div>
                </div>

                @if($product->average_rating)
                    <div class="stars">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg>
                        <span style="font-size:12px;color:var(--color-text);opacity:0.7;">{{ number_format($product->average_rating, 1) }}</span>
                    </div>
                @endif

                <h3 style="font-size:15px;margin:0;">{{ $product->name }}</h3>

                @if($product->default_variant)
                    <div style="display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;">
                        <span style="font-weight:700;">&#8377;{{ number_format($product->default_variant->price_minor / 100, 0) }}</span>
                    </div>
                @endif
            </a>
        @endforeach
    </div>
</section>

<section class="wrap story-section" id="story">
    <div class="story-grid">
        <figure class="washed">
            <img src="{{ asset('design/story-photo.jpg') }}" alt="Hands packing pickle jars" style="width:100%;aspect-ratio:4/5;object-fit:cover;">
        </figure>
        <div>
            <span class="tag tag-accent-2">Our story</span>
            <h2>A kitchen in Kerala, not a factory.</h2>
            <p>Every business has a beginning. Ours started with a family recipe and a few curious friends. One taste led to another, and soon we were making pickles for more than just ourselves. We still prepare every order by hand, with ingredients we’d feed our own family.</p>
            <div class="story-stats">
                <div><p>0</p><p>Preservatives</p></div>
                <div><p>100%</p><p>Handmade</p></div>
            </div>
        </div>
    </div>
</section>

<section class="wrap quote-section">
    <blockquote>&ldquo;It tastes exactly like the pickle my ammachi used to make. I order six jars at a time now.&rdquo;</blockquote>
    <figcaption style="font-size:13px;opacity:0.65;margin-top:14px;"> Anjali R., verified buyer</figcaption>
</section>


@endsection