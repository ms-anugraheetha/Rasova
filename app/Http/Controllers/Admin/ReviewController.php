<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        $status = $request->input('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('review', 'ilike', "%{$search}%")
                    ->orWhere('guest_name', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('first_name', 'ilike', "%{$search}%")
                            ->orWhere('last_name', 'ilike', "%{$search}%");
                    });
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('reviews', 'status', 'products'));
    }

    public function approve(Request $request, Review $review)
    {
        $review->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        $review->product->recalculateAverageRating();

        return back()->with('success', 'Review approved.');
    }

    public function destroy(Review $review)
    {
        $product = $review->product;
        $review->delete();
        $product->recalculateAverageRating();

        return back()->with('success', 'Review deleted.');
    }
}