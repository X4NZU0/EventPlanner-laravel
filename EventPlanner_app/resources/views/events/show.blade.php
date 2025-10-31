@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')


<div class="event-containerZ">

<a href="{{ route('events.index') }}" class="backs-link">← Back</a>

    <h1 class="event-title">{{ $event->event_name }}</h1>

    <p class="event-details">{{ $event->event_details }}</p>
    <p><strong>Location:</strong> {{ $event->event_location }}</p>
    <p><strong>Start:</strong> {{ $event->event_datestart }} {{ $event->event_timestart }}</p>
    <p><strong>End:</strong> {{ $event->event_dateend }} {{ $event->event_timeend }}</p>

    <p class="event-stats">Interested: {{ $interestCount }} | Comments: {{ $commentCount }}</p>

    <form action="{{ route('events.interested', $event->event_id) }}" method="POST" class="interested-form">
        @csrf
        <button type="submit" class="btn">
            ❤️ Interested ({{ $event->interest_count ?? 0 }})
        </button>
    </form>

    @if(!empty($event->event_img))
            <div class="event-image">
                <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}">
            </div>
            @endif

    <br><br>
    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
                            @csrf
                            <textarea name="event_comment" placeholder="Write a comment..." required></textarea>
                            <button type="submit">Post</button>
                        </form>

    <br><br><h3 class="comments-title">Comments</h3>
    <div class="comments-list">
        @forelse ($comments as $comment)
            <div class="comment-card">
                <p>{{ $comment->event_comment }}</p>
                <small>{{ $comment->comment_datetime }}</small>
            </div>
            
        @empty
            <p class="no-comments">No comments yet.</p>
        @endforelse
    </div>

    <a href="{{ route('events.index') }}" class="back-link">← Back to Events</a>
</div>
@endsection
