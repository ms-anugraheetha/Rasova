<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $bestsellers = Product::query()
            ->with('variants')
            ->latest()
            ->take(4)
            ->get();

        return view('home', [
            'categories' => collect(), // TODO: replace once we confirm your Category model/column setup
            'bestsellers' => $bestsellers,
            'footerCategories' => collect(),
            'cartCount' => session('cart.count', 0),
        ]);
    }
}