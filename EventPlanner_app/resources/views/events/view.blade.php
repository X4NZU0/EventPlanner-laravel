@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ $event->event_name }}</h2>

    <p><strong>Details:</strong> {{ $event->event_details }}</p>
    <p><strong>Location:</strong> {{ $event->event_location }}</p>
    <p><strong>Start:</strong> {{ $event->event_datestart }} {{ $event->event_timestart }}</p>
    <p><strong>End:</strong> {{ $event->event_dateend }} {{ $event->event_timeend }}</p>

    <hr>

    {{-- Interested Count --}}
    <p><strong>Interested Users:</strong> {{ $interestCount ?? 0 }}</p>

    {{-- Comments --}}
    <h4>Comments</h4>
    @if(isset($commentCount) && $commentCount > 0)
        <ul>
            @foreach(DB::table('event_comments')->where('event_id', $event->event_id)->get() as $comment)
                <li>{{ $comment->comment_text }} <small>by {{ $comment->user_name ?? 'User' }}</small></li>
            @endforeach
        </ul>
    @else
        <p>No comments yet.</p>
    @endif

    <hr>

    {{-- Add a comment (all logged-in users) --}}
    @if(session()->has('user'))
    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
        @csrf
        <div style="margin-bottom: 10px;">
            <textarea name="comment_text" rows="3" placeholder="Add a comment..." required></textarea>
        </div>
        <button type="submit">Post Comment</button>
    </form>
    @endif

    <br>
    <a href="{{ route('events.index') }}">← Back to Events</a>
</div>
@endsection