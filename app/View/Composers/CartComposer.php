<?php

namespace App\View\Composers;

use App\Services\CartResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartComposer
{
    public function __construct(
        protected CartResolver $cartResolver,
        protected Request $request,
    ) {
    }

    public function compose(View $view): void
    {
        $cart = $this->cartResolver->resolve($this->request);

        $view->with('cartCount', $cart->items()->sum('quantity'));
    }
}