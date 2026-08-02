<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Live typeahead endpoint — returns a small set of matching products as
     * JSON for the nav search overlay. Case-insensitive across name,
     * description, and category name, ordered by relevance (name matches
     * first, then description/category matches).
     */
    public function api(Request $request)
    {
        $term = trim((string) $request->input('q', ''));

        if ($term === '') {
            return response()->json(['results' => []]);
        }

        $products = $this->searchQuery($term)
            ->with(['category', 'variants' => fn ($v) => $v->where('is_active', true)->orderBy('price_minor')])
            ->limit(6)
            ->get();

        $results = $products->map(function (Product $product) {
            $variant = $product->default_variant;

            return [
                'name' => $product->name,
                'slug' => $product->slug,
                'url' => route('products.show', $product->slug),
                'image' => $product->primary_image_url,
                'category' => $product->category->name ?? null,
                'price' => $variant ? number_format($variant->price_minor / 100, 0) : null,
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Full search results page — reached by pressing Enter in the search
     * overlay, or by sharing/bookmarking a search URL directly.
     */
    public function results(Request $request)
    {
        $term = trim((string) $request->input('q', ''));

        $products = $term !== ''
            ? $this->searchQuery($term)
                ->with(['category', 'variants' => fn ($v) => $v->where('is_active', true)->orderBy('price_minor'), 'images'])
                ->withCount(['reviews as review_count' => fn ($q) => $q->where('status', 'approved')->where('is_hidden', false)])
                ->paginate(12)
                ->withQueryString()
            : Product::where('is_available', true)->whereRaw('1=0')->paginate(12); // empty paginator for a blank query

        return view('search.results', ['products' => $products, 'term' => $term]);
    }

    /**
     * Shared search query: case-insensitive across name, description, and
     * category name, with simple relevance ordering (name prefix matches
     * rank highest, then name contains, then description/category matches).
     */
    protected function searchQuery(string $term)
    {
        $like = '%' . $term . '%';
        $prefixLike = $term . '%';

        return Product::query()
            ->where('is_available', true)
            ->where(function ($q) use ($like) {
                $q->where('name', 'ilike', $like)
                    ->orWhere('description', 'ilike', $like)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'ilike', $like));
            })
            ->selectRaw('products.*, CASE WHEN name ILIKE ? THEN 1 WHEN name ILIKE ? THEN 2 ELSE 3 END as relevance', [$prefixLike, $like])
            ->orderBy('relevance')
            ->orderBy('name');
    }
}