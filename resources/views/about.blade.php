@extends('layouts.storefront')

@section('title', 'Our Story — Rasova')

@section('extra-styles')
/* ===== Scroll reveal ===== */
[data-reveal] { opacity: 0; transition: opacity 0.8s ease, transform 0.8s ease; }
[data-reveal="up"] { transform: translateY(24px); }
[data-reveal="left"] { transform: translateX(-24px); }
[data-reveal="right"] { transform: translateX(24px); }
[data-reveal].revealed { opacity: 1; transform: translate(0, 0); }

.icon-outline { color: var(--color-accent-700); stroke-width: 1.5; }

/* ===== Section dividers / rhythm ===== */
.section-divider { border: none; border-top: 1px solid var(--color-divider); max-width: 1440px; margin: 0 auto; }

/* ===== Section 1: Our Story ===== */
.story-section { padding: 56px 0 72px; }
.story-layout { display: flex; flex-direction: column; gap: 32px; }
.story-text .tag { margin-bottom: 16px; display: inline-block; }
.story-text h1 { font-size: clamp(28px, 7vw, 42px); margin: 0 0 18px; line-height: 1.15; }
.story-text p { font-size: 16px; opacity: 0.76; line-height: 1.7; margin: 0 0 16px; max-width: 56ch; }
.story-stats { display: flex; gap: 32px; flex-wrap: wrap; margin-top: 24px; }
.story-stats p:first-child { font-size: 26px; margin: 0; font-family: var(--font-heading); }
.story-stats p:last-child { font-size: 12px; opacity: 0.55; margin: 4px 0 0; letter-spacing: 0.02em; }
.story-figure { border-radius: 24px; overflow: hidden; box-shadow: var(--shadow-lg); margin: 0; }
.story-figure img { width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; }

/* ===== Section 2: Our Promise ===== */
.promise-section { padding: 72px 0; background: var(--color-surface); }
.promise-head { text-align: center; margin-bottom: 48px; }
.promise-head h2 { font-size: clamp(24px, 6vw, 34px); margin: 0 0 12px; }
.promise-head p { font-size: 15px; opacity: 0.68; max-width: 48ch; margin: 0 auto; }
.promise-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
.promise-card {
    background: var(--color-bg); border-radius: 24px; padding: 40px 28px;
    border: 1px solid var(--color-divider); box-shadow: 0 1px 3px color-mix(in srgb, var(--color-text) 5%, transparent);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex; flex-direction: column; align-items: center; text-align: center; height: 100%;
}
.promise-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
.promise-icon-wrap {
    width: 64px; height: 64px; border-radius: 50%; display: grid; place-items: center;
    background: var(--color-accent-2-100); margin-bottom: 20px;
}
.promise-card h3 { font-size: 16px; margin: 0 0 10px; letter-spacing: 0.01em; }
.promise-card p { font-size: 14px; opacity: 0.68; line-height: 1.6; margin: 0; }

/* ===== Section 3: Timeline ===== */
.timeline-section { padding: 72px 0; }
.timeline-head { text-align: center; margin-bottom: 56px; }
.timeline-head h2 { font-size: clamp(24px, 6vw, 34px); margin: 0 0 12px; }
.timeline-head p { font-size: 15px; opacity: 0.68; max-width: 48ch; margin: 0 auto; }
.timeline { display: flex; flex-direction: column; gap: 0; }
.timeline-step { display: flex; align-items: flex-start; gap: 20px; padding-bottom: 40px; position: relative; }
.timeline-step:last-child { padding-bottom: 0; }
.timeline-step-number {
    font-size: 11px; font-weight: 700; letter-spacing: 0.08em; color: var(--color-accent-700);
    opacity: 0.6; margin-bottom: 8px; display: block;
}
.timeline-icon {
    width: 58px; height: 58px; border-radius: 50%; background: var(--color-bg);
    border: 1.5px solid var(--color-accent-700); display: grid; place-items: center;
    flex-shrink: 0; position: relative; z-index: 1;
}
.timeline-connector {
    position: absolute; left: 28px; top: 66px; bottom: -40px; width: 1px;
    background: var(--color-accent-700); opacity: 0.35;
}
.timeline-step:last-child .timeline-connector { display: none; }
.timeline-content h3 { font-size: 16px; margin: 0 0 4px; }
.timeline-content p { font-size: 13px; opacity: 0.6; margin: 0; }

/* ===== Section 4: FAQ ===== */
.faq-section { padding: 72px 0; }
.faq-head { text-align: center; margin-bottom: 40px; }
.faq-head h2 { font-size: clamp(24px, 6vw, 34px); margin: 0; }
.faq-list { max-width: 720px; margin: 0 auto; display: flex; flex-direction: column; gap: 12px; }
.faq-item { border: 1px solid var(--color-divider); border-radius: 16px; overflow: hidden; transition: border-color 0.2s ease; }
.faq-item.open { border-color: var(--color-accent-700); }
.faq-question {
    width: 100%; text-align: left; background: var(--color-bg); border: none; cursor: pointer;
    padding: 20px 24px; font-size: 15px; font-weight: 500; font-family: inherit; color: var(--color-text);
    display: flex; justify-content: space-between; align-items: center; gap: 16px;
}
.faq-question svg { flex-shrink: 0; transition: transform 0.3s ease; color: var(--color-accent-700); }
.faq-item.open .faq-question svg { transform: rotate(180deg); }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
.faq-answer-inner { padding: 0 24px 22px; font-size: 14px; opacity: 0.68; line-height: 1.7; }

/* ===== Section 5: Final CTA ===== */
.about-cta-section { padding: 80px 0; text-align: center; }
.about-cta-section h2 { font-size: clamp(26px, 6vw, 38px); margin: 0 0 14px; }
.about-cta-section p { font-size: 15px; opacity: 0.7; max-width: 46ch; margin: 0 auto 32px; }
.about-cta-actions { display: flex; flex-direction: column; gap: 12px; max-width: 320px; margin: 0 auto; }
.about-cta-actions .btn { min-height: 48px; }

/* ===== TABLET ===== */
@media (min-width: 768px) {
    .promise-grid { grid-template-columns: repeat(2, 1fr); }
    .about-cta-actions { flex-direction: row; max-width: none; justify-content: center; }
    .about-cta-actions .btn { min-width: 180px; }
}

/* ===== DESKTOP ===== */
@media (min-width: 1024px) {
    .story-layout { flex-direction: row; align-items: center; gap: 64px; }
    .story-text { flex: 0 0 42%; }
    .story-figure-col { flex: 0 0 54%; }

    .promise-grid { grid-template-columns: repeat(4, 1fr); }

    .timeline { flex-direction: row; align-items: flex-start; justify-content: space-between; }
    .timeline-step { flex-direction: column; align-items: center; text-align: center; flex: 1; padding-bottom: 0; }
    .timeline-step-number { text-align: center; }
    .timeline-content { max-width: 170px; }
    .timeline-connector {
        left: 58px; right: -100%; top: 29px; bottom: auto; width: auto; height: 1px;
    }
}
@endsection

@section('content')

{{-- Section 1: Our Story --}}
<section class="wrap story-section">
    <div class="story-layout">
        <div class="story-text" data-reveal="left">
            <span class="tag tag-accent">Our story</span>
            <h1>A kitchen in Kerala, not a factory.</h1>
            <p>Rasova started in one family kitchen, sun-drying mangoes on the terrace and pressing them in small clay jars. Every batch we ship today is still made that way; by hand, in small runs, with ingredients we'd feed our own family.</p>
            <p>No preservatives, no shortcuts, no factory lines; just fresh ingredients and traditional methods.</p>

            <div class="story-stats">
                <div><p>0</p><p>Preservatives</p></div>
                <div><p>100%</p><p>Handmade</p></div>
            </div>
        </div>

        <div class="story-figure-col" data-reveal="right">
            <figure class="story-figure">
                <img src="{{ asset('design/story-photo.png') }}" alt="Hands packing pickle jars" loading="lazy">
            </figure>
        </div>
    </div>
</section>

<hr class="section-divider">

{{-- Section 2: Our Promise --}}
<section class="promise-section">
    <div class="wrap">
        <div class="promise-head" data-reveal="up">
            <h2>Our Promise</h2>
            <p>Everything we make is guided by simple values that never change.</p>
        </div>

        <div class="promise-grid">
            <div class="promise-card" data-reveal="up">
                <div class="promise-icon-wrap">
                    <svg class="icon-outline" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                    </svg>
                </div>
                <h3>No Preservatives</h3>
                <p>Only honest ingredients. Nothing artificial.</p>
            </div>
            <div class="promise-card" data-reveal="up">
                <div class="promise-icon-wrap">
                    <svg class="icon-outline" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v0"></path>
                        <path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v2"></path>
                        <path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v8"></path>
                        <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-4.53a1.63 1.63 0 0 1 2.29-2.3L6 14"></path>
                    </svg>
                </div>
                <h3>Handmade with Care</h3>
                <p>Every batch is prepared by hand using traditional Kerala methods.</p>
            </div>
            <div class="promise-card" data-reveal="up">
                <div class="promise-icon-wrap">
                    <svg class="icon-outline" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16.5 14.5"></polyline>
                    </svg>
                </div>
                <h3>Prepared After You Order</h3>
                <p>We don't prepare months in advance. Freshness comes first.</p>
            </div>
            <div class="promise-card" data-reveal="up">
                <div class="promise-icon-wrap">
                    <svg class="icon-outline" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                    </svg>
                </div>
                <h3>Made for Family First</h3>
                <p>If we wouldn't serve it at our own table, we won't send it to yours.</p>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

{{-- Section 3: From Kitchen to Your Door --}}
<section class="timeline-section">
    <div class="wrap">
        <div class="timeline-head" data-reveal="up">
            <h2>From Kitchen to Your Door</h2>
            <p>Every order follows the same careful journey.</p>
        </div>

        <div class="timeline">
            <div class="timeline-step" data-reveal="up">
                <div>
                    <span class="timeline-step-number">01</span>
                    <div class="timeline-icon">
                        <svg class="icon-outline" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="timeline-connector"></div>
                <div class="timeline-content">
                    <h3>Fresh Ingredients</h3>
                    <p>Sourced at their peak, never frozen or stored long-term.</p>
                </div>
            </div>
            <div class="timeline-step" data-reveal="up">
                <div>
                    <span class="timeline-step-number">02</span>
                    <div class="timeline-icon">
                        <svg class="icon-outline" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v0"></path>
                            <path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v2"></path>
                            <path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v8"></path>
                            <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-4.53a1.63 1.63 0 0 1 2.29-2.3L6 14"></path>
                        </svg>
                    </div>
                </div>
                <div class="timeline-connector"></div>
                <div class="timeline-content">
                    <h3>Prepared by Hand</h3>
                    <p>Cut, cooked, and mixed the way it's always been done.</p>
                </div>
            </div>
            <div class="timeline-step" data-reveal="up">
                <div>
                    <span class="timeline-step-number">03</span>
                    <div class="timeline-icon">
                        <svg class="icon-outline" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 3v3.5a2 2 0 0 1-.6 1.4L4.6 11.7A2 2 0 0 0 4 13.1V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5.9a2 2 0 0 0-.6-1.4L15.6 7.9a2 2 0 0 1-.6-1.4V3"></path>
                            <path d="M7 3h10"></path>
                            <path d="M6 14h12"></path>
                        </svg>
                    </div>
                </div>
                <div class="timeline-connector"></div>
                <div class="timeline-content">
                    <h3>Traditional Spice Blend</h3>
                    <p>Family recipes, measured by feel, not just a formula.</p>
                </div>
            </div>
            <div class="timeline-step" data-reveal="up">
                <div>
                    <span class="timeline-step-number">04</span>
                    <div class="timeline-icon">
                        <svg class="icon-outline" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16.5 9.4 7.55 4.24"></path>
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                            <path d="m3.3 7 8.7 5 8.7-5"></path>
                            <path d="M12 22V12"></path>
                        </svg>
                    </div>
                </div>
                <div class="timeline-connector"></div>
                <div class="timeline-content">
                    <h3>Packed with Care</h3>
                    <p>Sealed fresh in small batches, ready to travel.</p>
                </div>
            </div>
            <div class="timeline-step" data-reveal="up">
                <div>
                    <span class="timeline-step-number">05</span>
                    <div class="timeline-icon">
                        <svg class="icon-outline" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 17h4V5H2v12h3"></path>
                            <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"></path>
                            <circle cx="7.5" cy="17.5" r="2.5"></circle>
                            <circle cx="17.5" cy="17.5" r="2.5"></circle>
                        </svg>
                    </div>
                </div>
                <div class="timeline-content">
                    <h3>Delivered Fresh</h3>
                    <p>Straight from our kitchen to your door.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

{{-- Section 4: FAQ --}}
<section class="faq-section">
    <div class="wrap">
        <div class="faq-head" data-reveal="up">
            <h2>Frequently Asked Questions</h2>
        </div>

        <div class="faq-list" data-reveal="up">
            <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false">
                    Do you use preservatives?
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Never. Every jar is made with honest ingredients and no artificial preservatives the same way it's always been made in Kerala kitchens.</div>
                </div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false">
                    When do you prepare the pickles?
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Only after you order. We don't prepare in bulk months ahead  freshness always comes first, even if it means a short wait.</div>
                </div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false">
                    How should I store the pickle?
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Keep it in a cool, dry place away from direct sunlight. Once opened, always use a clean, dry spoon to keep it fresh for longer.</div>
                </div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false">
                    How long does shipping take?
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"></path></svg>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">Most orders are prepared and dispatched within a few days, with delivery typically taking 3 - 7 days depending on your location.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="section-divider">

{{-- Section 5: Final CTA --}}
<section class="about-cta-section" data-reveal="up">
    <div class="wrap">
        <h2>Bring Kerala Home</h2>
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
    // Scroll reveal animations
    (function () {
        var targets = document.querySelectorAll('[data-reveal]');
        if (!('IntersectionObserver' in window)) {
            targets.forEach(function (el) { el.classList.add('revealed'); });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        targets.forEach(function (el) { observer.observe(el); });
    })();

    // FAQ accordion — only one open at a time
    (function () {
        var items = document.querySelectorAll('.faq-item');
        items.forEach(function (item) {
            var question = item.querySelector('.faq-question');
            var answer = item.querySelector('.faq-answer');

            question.addEventListener('click', function () {
                var isOpen = item.classList.contains('open');

                items.forEach(function (other) {
                    other.classList.remove('open');
                    other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                    other.querySelector('.faq-answer').style.maxHeight = null;
                });

                if (!isOpen) {
                    item.classList.add('open');
                    question.setAttribute('aria-expanded', 'true');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });
    })();
</script>
@endpush