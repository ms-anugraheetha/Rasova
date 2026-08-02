@extends('layouts.admin')

@section('title', 'Reviews')

@section('extra-styles')
.admin-status-tabs { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
.admin-status-tabs a {
    padding: 8px 16px; border-radius: 10px; font-size: 13px; background: var(--color-surface);
}
.admin-status-tabs a.active { background: var(--color-accent); color: white; }
.admin-filter-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.admin-filter-row input, .admin-filter-row select { min-height: 40px; }
.admin-review-row td { vertical-align: top; }
.admin-review-product { display: flex; align-items: center; gap: 10px; }
.admin-review-product img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
.admin-stars-inline { color: var(--color-star); font-size: 13px; white-space: nowrap; }
.admin-review-text { max-width: 320px; font-size: 13px; opacity: 0.85; }
.admin-badge { font-size: 11px; padding: 2px 8px; border-radius: 6px; display: inline-block; }
.admin-badge.pending { background: var(--color-accent-2-100); }
.admin-badge.approved { background: color-mix(in srgb, green 15%, transparent); color: green; }
.admin-actions-cell { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
@endsection

@section('content')

<h1>Reviews</h1>

<div class="admin-status-tabs">
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="{{ $status === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" class="{{ $status === 'approved' ? 'active' : '' }}">Approved</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'all'])) }}" class="{{ $status === 'all' ? 'active' : '' }}">All</a>
</div>

<form method="GET" class="admin-filter-row">
    <input type="hidden" name="status" value="{{ $status }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search review text or customer..." class="admin-input" style="flex:1;min-width:200px;">

    <select name="product_id" class="admin-select" style="max-width:220px;">
        <option value="">All products</option>
        @foreach ($products as $product)
            <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
        @endforeach
    </select>

    <select name="rating" class="admin-select" style="max-width:140px;">
        <option value="">Any rating</option>
        @for ($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} star</option>
        @endfor
    </select>

    <button type="submit" class="btn btn-primary" style="min-height:40px;padding:0 18px;">Filter</button>
</form>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Customer</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $review)
                <tr class="admin-review-row">
                    <td>
                        <div class="admin-review-product">
                            <img src="{{ $review->product->primary_image_url ?? '' }}" alt="{{ $review->product->name ?? '' }}">
                            <span>{{ $review->product->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $review->reviewer_name }}</td>
                    <td class="admin-stars-inline">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                    <td class="admin-review-text">{{ $review->review }}</td>
                    <td><span class="admin-badge {{ $review->status }}">{{ ucfirst($review->status) }}</span></td>
                    <td>{{ $review->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="admin-actions-cell">
                            @if ($review->status !== 'approved')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Approve</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete this review permanently? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-link admin-btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="opacity:0.6;">No reviews found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $reviews->links() }}
</div>

@endsection