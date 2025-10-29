<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .event-card { background: #f0f0f0; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        a { text-decoration: none; color: #007bff; margin-left: 10px; }
        .btn { display: inline-block; padding: 6px 12px; border-radius: 5px; text-decoration: none; color: white; font-size: 0.9em; }
        .btn-edit { background-color: orange; }
        .btn-delete { background-color: crimson; }
        .btn-create { background-color: #007bff; }
        .btn-logout { background-color: gray; }
        form { display: inline; }
        textarea { width: 100%; margin: 5px 0; padding: 5px; }
        button { cursor: pointer; padding: 5px 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>All Events</h2>
        <div>
            {{-- Show Create Event button if admin is logged in --}}
            @if(session()->has('admin'))
                <a href="{{ route('events.create') }}" class="btn btn-create">➕ Create Event</a>
            @endif
            <a href="{{ route('logout') }}" class="btn btn-logout">Logout</a>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    {{-- Events list --}}
    @forelse($events as $event)
        <div class="event-card">
            <h3>
                <a href="{{ route('events.show', $event->event_id) }}">{{ $event->event_name }}</a>
            </h3>
            <p>{{ $event->event_details }}</p>
            <p><strong>Time:</strong> {{ $event->event_timestart }} - {{ $event->event_timeend }}</p>
            <p><strong>Date:</strong> {{ $event->event_datestart }} - {{ $event->event_dateend }}</p>
            <p><strong>Location:</strong> {{ $event->event_location }}</p>
            <p><strong>Posted by:</strong> {{ $event->admin_name }}</p>

            {{-- Admin actions --}}
            @if(session()->has('admin'))
                <div style="margin-top:10px;">
                    <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-edit">✏️ Edit</a>
                    <form action="{{ route('events.destroy', $event->event_id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">🗑️ Delete</button>
                    </form>
                </div>
            @endif

            {{-- User actions --}}
            @if(session()->has('user'))
                <div style="margin-top:10px;">
                    <form action="{{ route('events.interested', $event->event_id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Interested ({{ $event->interest_count ?? 0 }})</button>
                    </form>

                    <button type="button" onclick="toggleComments({{ $event->event_id }})">
                        Comments ({{ $event->comment_count ?? 0 }})
                    </button>

                    <div id="comments-{{ $event->event_id }}" style="display:none; margin-top:5px;">
                        <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
                            @csrf
                            <textarea name="comment_text" placeholder="Write a comment..." required></textarea>
                            <button type="submit">Post</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <p>No events found.</p>
    @endforelse
</div>

<script>
function toggleComments(id) {
    const section = document.getElementById('comments-' + id);
    section.style.display = (section.style.display === 'none') ? 'block' : 'none';
}
</script>

</body>
</html>