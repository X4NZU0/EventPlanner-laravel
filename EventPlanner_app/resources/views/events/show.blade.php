@extends('layouts.app')

@section('content')
    <h1>{{ $event->event_name }}</h1>

    <p>{{ $event->event_details }}</p>
    <p><strong>Location:</strong> {{ $event->event_location }}</p>
    <p><strong>Start:</strong> {{ $event->event_datestart }} {{ $event->event_timestart }}</p>
    <p><strong>End:</strong> {{ $event->event_dateend }} {{ $event->event_timeend }}</p>

    <p>Interested: {{ $interestCount }} | Comments: {{ $commentCount }}</p>

    <form action="{{ route('events.interested', $event->event_id) }}" method="POST" style="margin-bottom:10px;">
        @csrf
        <button type="submit">Mark Interested</button>
    </form>

    <h3>Comments</h3>

    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
        @csrf
        <textarea name="comment_text" placeholder="Write a comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>

    <div style="margin-top:10px;">
        @forelse ($comments as $comment)
            <div style="border-bottom:1px solid #ccc; padding:5px 0;">
                <p>{{ $comment->event_comment }}</p>
                <small>{{ $comment->comment_datetime }}</small>
            </div>
        @empty
            <p>No comments yet.</p>
        @endforelse
    </div>

    <a href="{{ route('events.index') }}" style="display:block; margin-top:10px;">← Back to Events</a>
@endsection
