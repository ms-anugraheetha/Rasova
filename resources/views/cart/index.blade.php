@extends('layouts.storefront')

@section('title', 'Your Cart — Rasova')

@section('extra-styles')
.cart-header { padding: 28px 0 20px; }
.cart-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }

.cart-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 28px; }
.cart-empty { padding: 48px 0; text-align: center; }
.cart-empty p { opacity: 0.7; margin-bottom: 18px; }

.cart-list { display: flex; flex-direction: column; gap: 16px; }
.cart-item {
    display: flex; gap: 14px; align-items: center;
    padding: 14px; border-radius: 16px; background: var(--color-surface);
}
.cart-item-img { width: 72px; height: 72px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: var(--color-bg); }
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
.cart-item-body { flex: 1; min-width: 0; }
.cart-item-body h3 { font-size: 15px; margin: 0 0 2px; }
.cart-item-body p { font-size: 13px; opacity: 0.65; margin: 0 0 10px; }

.cart-item-controls { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.qty-form { display: flex; align-items: center; gap: 8px; }
.qty-form input {
    width: 52px; min-height: 40px; text-align: center;
    border: 1px solid var(--color-divider); border-radius: 10px; background: var(--color-bg); color: inherit;
}
.qty-update-btn {
    min-height: 40px; padding: 0 12px; font-size: 13px;
    border-radius: 10px; border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; cursor: pointer;
}
.cart-item-price { font-weight: 700; font-size: 15px; white-space: nowrap; }
.cart-remove-btn {
    border: none; background: none; color: var(--color-error, #b3132d);
    font-size: 13px; cursor: pointer; padding: 0; min-height: 40px;
}

.cart-summary {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 20px; border-top: 1px solid var(--color-divider); gap: 16px; flex-wrap: wrap;
}
.cart-summary p { font-size: 20px; font-weight: 700; margin: 0; }
.cart-summary .btn { min-height: 48px; padding: 0 28px; }

/* ===== TABLET — min-width 768px ===== */
@media (min-width: 768px) {
    .cart-item { padding: 18px; }
    .cart-item-img { width: 88px; height: 88px; }
}
@endsection

@section('content')

<header class="wrap cart-header">
    <h1>Your Cart</h1>
</header>

@if (session('success'))
    <div class="wrap" style="padding-bottom:16px;">
        <p style="color:var(--color-success, green); font-size:13px;">{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="wrap" style="padding-bottom:16px;">
        <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ session('error') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="wrap" style="padding-bottom:16px;">
        @foreach ($errors->all() as $error)
            <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="wrap cart-layout">
    @forelse ($items as $item)
        @if ($loop->first)
            <div class="cart-list">
        @endif

        @php $product = $item->productVariant?->product; @endphp

        <div class="cart-item">
            @if ($product)
                <div class="cart-item-img">
                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                </div>

                <div class="cart-item-body">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $item->productVariant->weight }}</p>

                    <div class="cart-item-controls">
    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="qty-form">
        @csrf
        @method('PATCH')
        <div class="qty-field">
            <label for="quantity-{{ $item->id }}">Quantity:</label>
            <input
                type="number"
                id="quantity-{{ $item->id }}"
                name="quantity"
                value="{{ $item->quantity }}"
                min="1"
            >
        </div>
        <button type="submit" class="qty-update-btn">Update</button>
    </form>

    <span class="cart-item-price">&#8377;{{ number_format($item->productVariant->price_minor * $item->quantity / 100, 2) }}</span>

    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="cart-remove-btn" aria-label="Remove {{ $item->productVariant->product->name ?? 'item' }} from cart">Remove</button>
    </form>
</div>
                </div>
            @else
                <div class="cart-item-body">
                    <h3 style="opacity:0.6;">This item is no longer available</h3>
                    <p>It may have been removed from the store.</p>

                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cart-remove-btn">Remove</button>
                    </form>
                </div>
            @endif
        </div>

        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="cart-empty">
            <p>Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Shop pickles</a>
        </div>
    @endforelse

    @if ($items->isNotEmpty())
        <div class="cart-summary">
            <p>Subtotal: &#8377;{{ number_format($subtotal / 100, 2) }}</p>
            <a href="{{ route('checkout.show') }}" class="btn btn-primary">Checkout</a>
        </div>
    @endif
</div>

@endsection