<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewHelpfulVote;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    protected const MAX_IMAGES = 5;

    /**
     * Creates a new review, or updates the customer's existing one for this
     * product (one review per customer per product; edits are allowed and
     * re-queue the review for moderation).
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'review' => 'required|string|max:2000',
            'images' => 'nullable|array|max:' . self::MAX_IMAGES,
            'images.*' => 'image|max:4096',
        ]);

        $deliveredOrderItem = $this->findDeliveredPurchase($request->user()->id, $product->id);

        if (! $deliveredOrderItem) {
            return back()->with('error', 'You can only review products from an order that has been delivered to you.');
        }

        $existing = Review::where('product_id', $product->id)
            ->where('user_id', $request->user()->id)
            ->first();

        $review = $existing ?? new Review([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        $review->fill([
            'order_id' => $deliveredOrderItem->order_id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'review' => $validated['review'],
            'verified_purchase' => true,
            // Edits go back through moderation rather than staying published as-is.
            'status' => 'pending',
            'is_hidden' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);
        $review->save();

        if ($request->hasFile('images')) {
            // Replace previous images entirely rather than appending —
            // keeps "up to 5 images" simple and avoids unbounded growth across edits.
            foreach ($review->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image);
                $oldImage->delete();
            }

            foreach ($request->file('images') as $file) {
                $path = $file->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => $path,
                ]);
            }
        }

        $product->recalculateAverageRating();

        $message = $existing
            ? 'Your review has been updated and will appear once approved.'
            : 'Thanks! Your review has been submitted and will appear once approved.';

        return back()->with('success', $message);
    }

    public function markHelpful(Request $request, Review $review)
    {
        if ($review->user_id === $request->user()->id) {
            return back()->with('error', "You can't mark your own review as helpful.");
        }

        $vote = ReviewHelpfulVote::where('review_id', $review->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($vote) {
            $vote->delete();
        } else {
            ReviewHelpfulVote::create([
                'review_id' => $review->id,
                'user_id' => $request->user()->id,
            ]);
        }

        return back();
    }

    /**
     * Finds a delivered order item for this user/product combination —
     * the basis for both "verified purchase" and review eligibility.
     */
    protected function findDeliveredPurchase(int $userId, int $productId): ?OrderItem
    {
        return OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('order_status', 'delivered');
            })
            ->latest()
            ->first();
    }
}