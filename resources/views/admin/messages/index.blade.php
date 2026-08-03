@extends('layouts.admin')

@section('title', 'Messages')

@section('extra-styles')
.admin-filter-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.admin-filter-row input { min-height: 40px; }
.admin-badge { font-size: 11px; padding: 2px 8px; border-radius: 6px; display: inline-block; }
.admin-badge.new { background: var(--color-accent-2-100); color: var(--color-accent-700); font-weight: 600; }
.admin-badge.read { background: var(--color-surface); opacity: 0.6; }
.admin-actions-cell { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.admin-message-row.unread td { font-weight: 600; }
@endsection

@section('content')

<h1>Messages</h1>

<form method="GET" class="admin-filter-row">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="admin-input" style="flex:1;min-width:220px;">
    <button type="submit" class="btn btn-primary" style="min-height:40px;padding:0 18px;">Search</button>
</form>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $message)
                <tr class="admin-message-row {{ $message->status === 'new' ? 'unread' : '' }}">
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->subject ?: '—' }}</td>
                    <td>{{ $message->created_at->format('M j, Y g:ia') }}</td>
                    <td><span class="admin-badge {{ $message->status }}">{{ $message->status === 'new' ? 'New' : 'Read' }}</span></td>
                    <td>
                        <div class="admin-actions-cell">
                            <a href="{{ route('admin.messages.show', $message->id) }}" class="admin-btn-link">View</a>
                            @if ($message->status === 'new')
                                <form method="POST" action="{{ route('admin.messages.markRead', $message->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="admin-btn-link">Mark as Read</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" data-confirm="Delete this message permanently?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-link admin-btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="opacity:0.6;">No messages found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">
    {{ $messages->links() }}
</div>

@endsection