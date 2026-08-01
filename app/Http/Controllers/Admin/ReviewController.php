<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'reply']);

        $status = $request->input('status', 'pending');
        if ($status === 'hidden') {
            $query->where('is_hidden', true);
        } elseif ($status !== 'all') {
            $query->where('status', $status)->where('is_hidden', false);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('review', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%")
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

        if ($request->input('verified') === '1') {
            $query->where('verified_purchase', true);
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

    public function reject(Review $review)
    {
        $review->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
        ]);

        $review->product->recalculateAverageRating();

        return back()->with('success', 'Review rejected.');
    }

    public function hide(Review $review)
    {
        $review->update(['is_hidden' => true]);
        $review->product->recalculateAverageRating();

        return back()->with('success', 'Review hidden from the public site.');
    }

    public function unhide(Review $review)
    {
        $review->update(['is_hidden' => false]);
        $review->product->recalculateAverageRating();

        return back()->with('success', 'Review is visible again.');
    }

    public function reply(Request $request, Review $review)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        ReviewReply::updateOrCreate(
            ['review_id' => $review->id],
            ['admin_id' => $request->user()->id, 'reply' => $validated['reply']]
        );

        return back()->with('success', 'Reply saved.');
    }

    public function destroy(Review $review)
    {
        $product = $review->product;
        $review->delete();
        $product->recalculateAverageRating();

        return back()->with('success', 'Review deleted.');
    }
}