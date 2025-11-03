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

    {{-- Interested button --}}
    <form action="{{ route('events.interested', $event->event_id) }}" method="POST" class="interested-form">
        @csrf
        <button type="submit" class="btn">❤️ Interested ({{ $interestCount }})</button>
    </form>

    @if($event->event_img)
        <div class="event-image">
            <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}">
        </div>
    @endif

    {{-- Add top-level comment --}}
    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
        @csrf
        <textarea name="event_comment" placeholder="Write a comment..." required></textarea>
        <input type="hidden" name="parent_id" value="">
        <button type="submit">Post</button>
    </form>

    <h3 class="comments-title">Comments</h3>
    <div class="comments-list">

        @php
        // Recursive function to display comments
        function renderComments($comments, $parentId = null, $level = 0, $event) {
            foreach($comments->where('parent_id', $parentId) as $comment) {
                $margin = $level * 30;
                echo '<div class="comment-card" style="margin-left:'.$margin.'px;" id="comment-'.$comment->id.'">';
                echo '<p><strong>'.$comment->commenter_name.'</strong>: '.$comment->event_comment.'</p>';
                echo '<small>'.$comment->created_at.'</small>';

                // Like/Dislike
                echo '<form action="'.route('events.comment.like', [$event->event_id, $comment->id]).'" method="POST" style="display:inline;">';
                echo csrf_field();
                echo '<button type="submit">👍 '.$comment->likes.'</button>';
                echo '</form>';
                echo '<form action="'.route('events.comment.dislike', [$event->event_id, $comment->id]).'" method="POST" style="display:inline;">';
                echo csrf_field();
                echo '<button type="submit">👎 '.$comment->dislikes.'</button>';
                echo '</form>';

                // Reply button and form
                echo '<button type="button" onclick="toggleReplyForm('.$comment->id.')">Reply</button>';
                echo '<form id="reply-form-'.$comment->id.'" action="'.route('events.comment', $event->event_id).'" method="POST" style="display:none; margin-top:5px;">';
                echo csrf_field();
                echo '<textarea name="event_comment" placeholder="Write a reply..." required></textarea>';
                echo '<input type="hidden" name="parent_id" value="'.$comment->id.'">';
                echo '<button type="submit">Reply</button>';
                echo '</form>';

                // Delete button if owner
                $account = session('account');
                if(($account['role'] ?? '') === 'user' && ($account['id'] ?? 0) == $comment->user_id || 
                   ($account['role'] ?? '') === 'admin' && ($account['id'] ?? 0) == $comment->admin_id) {
                    echo '<form action="'.route('events.comment.delete', [$event->event_id, $comment->id]).'" method="POST" style="display:inline;">';
                    echo csrf_field();
                    echo method_field('DELETE');
                    echo '<button type="submit">🗑️ Delete</button>';
                    echo '</form>';
                }

                // Recursive call for child comments
                renderComments($comments, $comment->id, $level + 1, $event);

                echo '</div>';
            }
        }
        renderComments($comments, null, 0, $event);
        @endphp

    </div>
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>

@endsection
