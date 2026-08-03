@extends('layouts.admin')

@section('title', 'Message from ' . $message->name)

@section('extra-styles')
.admin-message-detail p { font-size: 14px; margin: 0 0 4px; }
.admin-message-detail .label { font-size: 12px; opacity: 0.55; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 14px; }
.admin-message-body { white-space: pre-line; line-height: 1.6; margin-top: 8px; }
@endsection

@section('content')

<a href="{{ route('admin.messages.index') }}" class="admin-btn-link">&larr; Back to messages</a>
<h1 style="margin-top:8px;">Message from {{ $message->name }}</h1>

<div class="admin-card admin-message-detail" style="max-width:640px;">
    <p class="label">From</p>
    <p>{{ $message->name }} &mdash; <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>

    @if ($message->subject)
        <p class="label" style="margin-top:20px;">Subject</p>
        <p>{{ $message->subject }}</p>
    @endif

    <p class="label" style="margin-top:20px;">Received</p>
    <p>{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>

    <p class="label" style="margin-top:20px;">Message</p>
    <p class="admin-message-body">{{ $message->message }}</p>
</div>

<form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" style="margin-top:16px;" data-confirm="Delete this message permanently?">
    @csrf
    @method('DELETE')
    <button type="submit" class="admin-btn-link admin-btn-danger">Delete this message</button>
</form>

@endsection