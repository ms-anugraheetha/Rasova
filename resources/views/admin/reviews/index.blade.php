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
.admin-stars-inline { color: var(--color-star); font-size: 13px; white-space: nowrap; }
.admin-review-text { max-width: 280px; font-size: 13px; opacity: 0.85; }
.admin-badge { font-size: 11px; padding: 2px 8px; border-radius: 6px; background: var(--color-accent-2-100); display: inline-block; }
.admin-actions-cell { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.admin-reply-block { margin-top: 8px; }
.admin-reply-block textarea {
    width: 100%; min-height: 60px; font-size: 12px; padding: 8px; border-radius: 8px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-family: inherit;
}
.admin-existing-reply { font-size: 12px; background: var(--color-accent-2-100); padding: 8px; border-radius: 8px; margin-top: 6px; }
@endsection

@section('content')

<h1>Reviews</h1>

<div class="admin-status-tabs">
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="{{ $status === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" class="{{ $status === 'approved' ? 'active' : '' }}">Approved</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'rejected'])) }}" class="{{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status' => 'hidden'])) }}" class="{{ $status === 'hidden' ? 'active' : '' }}">Hidden</a>
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

    <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
        <input type="checkbox" name="verified" value="1" @checked(request('verified') === '1')>
        Verified only
    </label>

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
                <th>Verified</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $review)
                <tr class="admin-review-row">
                    <td>{{ $review->product->name ?? 'N/A' }}</td>
                    <td>{{ $review->user->full_name ?? 'N/A' }}</td>
                    <td class="admin-stars-inline">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                    <td class="admin-review-text">
                        @if ($review->title)<strong>{{ $review->title }}</strong><br>@endif
                        {{ $review->review }}
                    </td>
                    <td>@if($review->verified_purchase)<span class="admin-badge">Verified</span>@endif</td>
                    <td>{{ $review->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="admin-actions-cell">
                            @if ($review->status !== 'approved')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Approve</button>
                                </form>
                            @endif
                            @if ($review->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.reviews.reject', $review->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Reject</button>
                                </form>
                            @endif
                            @if ($review->is_hidden)
                                <form method="POST" action="{{ route('admin.reviews.unhide', $review->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Unhide</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.reviews.hide', $review->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Hide</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete this review permanently?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admin-btn-link admin-btn-danger">Delete</button>
                            </form>
                        </div>

                        <div class="admin-reply-block">
                            @if ($review->reply)
                                <div class="admin-existing-reply">
                                    <strong>Your reply:</strong> {{ $review->reply->reply }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.reply', $review->id) }}">
                                @csrf
                                <textarea name="reply" placeholder="{{ $review->reply ? 'Update reply...' : 'Write a reply...' }}">{{ $review->reply->reply ?? '' }}</textarea>
                                <button type="submit" class="admin-btn-link" style="margin-top:4px;">{{ $review->reply ? 'Update reply' : 'Reply' }}</button>
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