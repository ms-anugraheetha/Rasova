<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewHelpfulVote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Creates a review for this product. Open to guests as well as logged-in
     * users — Rasova had many offline customers before the website existed,
     * so purchase-through-the-site is never required to leave a review.
     */
    public function store(Request $request, Product $product)
    {
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:2000',
            'is_anonymous' => 'nullable|boolean',
        ];
        if (! $request->user()) {
            $rules['guest_name'] = 'required|string|max:150';
            $rules['guest_email'] = 'nullable|email|max:255';
        }
        $validated = $request->validate($rules);

        if ($request->user()) {
            // One review per account per product — edits replace the previous
            // submission and go back through moderation.
            $review = Review::firstOrNew([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
            ]);
        } else {
            $review = new Review([
                'product_id' => $product->id,
            ]);
        }

        $review->fill([
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'guest_name' => $request->user() ? null : $validated['guest_name'],
            'guest_email' => $request->user() ? null : ($validated['guest_email'] ?? null),
            // The real name is still stored above (account or guest_name) even
            // when posting anonymously — only public display hides it.
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status' => 'pending',
            'is_hidden' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        try {
            $review->save();
        } catch (QueryException $e) {
            // Postgres unique_violation on (product_id, user_id). Two
            // simultaneous submissions from the same logged-in user can both
            // pass firstOrNew()'s "does a review exist?" check (since neither
            // has been saved yet at that point) and both try to insert — the
            // DB's unique constraint correctly blocks the second insert.
            // Rather than surfacing an error, re-fetch the review the other
            // request just created and apply this submission's data to it
            // instead, so the end result still matches the intended
            // "editing replaces the previous submission" behavior.
            if ($e->getCode() === '23505' && $request->user()) {
                $review = Review::where('product_id', $product->id)
                    ->where('user_id', $request->user()->id)
                    ->firstOrFail();
                $review->fill([
                    'rating' => $validated['rating'],
                    'review' => $validated['review'],
                    'is_anonymous' => $request->boolean('is_anonymous'),
                    'status' => 'pending',
                    'is_hidden' => false,
                    'approved_by' => null,
                    'approved_at' => null,
                ]);
                $review->save();
            } else {
                throw $e;
            }
        }

        $product->recalculateAverageRating();

        return back()->with('success', 'Thank you! Your review has been submitted and will appear after admin approval.');
    }

    public function markHelpful(Request $request, Review $review)
    {
        if ($request->user()->id === $review->user_id) {
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
}