<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        // Pre-fetch which products this user has already reviewed, so the
        // view can show "Edit review" vs "Write a Review" without N+1 queries.
        $reviewedProductIds = $request->user()->reviews()->pluck('product_id')->flip();

        return view('orders.index', compact('orders', 'reviewedProductIds'));
    }
}