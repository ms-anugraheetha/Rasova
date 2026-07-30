@extends('layouts.storefront')

@section('title', 'Our Story — Rasova')

@section('extra-styles')
.about-hero { padding: 40px 0 32px; }
.about-hero h1 { font-size: clamp(28px, 7vw, 44px); margin: 0 0 14px; }
.about-hero p { font-size: 16px; opacity: 0.78; max-width: 60ch; margin: 0 0 12px; }
.about-figure { border-radius: 24px; overflow: hidden; margin: 24px 0 40px; }
.about-figure img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }
.about-stats { display: flex; gap: 28px; flex-wrap: wrap; padding-bottom: 56px; }
.about-stats p:first-child { font-size: 28px; margin: 0; font-family: var(--font-heading); }
.about-stats p:last-child { font-size: 12px; opacity: 0.6; margin: 4px 0 0; }
@endsection

@section('content')
<section class="wrap about-hero">
    <span class="tag tag-accent">Our story</span>
    <h1>A kitchen in Kerala, not a factory.</h1>
    <p>Rasova started in one family kitchen, sun-drying mangoes on the terrace and pressing them in small clay jars. Every batch we ship today is still made that way &mdash; by hand, in small runs, with ingredients we'd feed our own family.</p>
    <p>No preservatives, no shortcuts, no factory lines &mdash; just recipes passed down and pickles pressed the slow way.</p>
</section>

<figure class="wrap about-figure">
    <img src="{{ asset('design/story-photo.jpg') }}" alt="Hands packing pickle jars">
</figure>

<section class="wrap about-stats">
    <div><p>30+</p><p>Years of recipes</p></div>
    <div><p>0</p><p>Preservatives</p></div>
    <div><p>100%</p><p>Handmade</p></div>
</section>
@endsection