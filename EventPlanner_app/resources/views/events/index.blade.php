<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Events Dashboard</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="dashboard-page">

@php
    $user = session('user');
    $displayName = $user['user_name'] ?? 'ADMIN';
    $userRoles = $user['roles'] ?? 1;
    $isAdmin = $userRoles == 2;
    $avatarName = $isAdmin ? 'Admin' : $displayName;

    $words = explode(' ', $avatarName);
    $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
    $colors = ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
    $colorIndex = abs(crc32($avatarName)) % count($colors);
    $bgColor = $colors[$colorIndex];
@endphp

<div class="dashboard-header">
    <div class="dashboard-logo">
        <span class="logo-ev">EV</span><span class="logo-plan">PLAN</span>
    </div>

    <div class="user-profile">
        <div class="user-info">
            <span class="user-name">{{ $isAdmin ? 'Admin' : $displayName }}</span>
            <span class="user-year">{{ $user['user_year_lvl'] ?? '' }}</span>
        </div>

        <div class="user-avatar">
            <div class="user-avatar-circle" style="background-color: {{ $bgColor }}">
                {{ $initials }}
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="header">
        <h2>All Events</h2>
        <div>
            <a href="{{ route('events.create') }}" class="btn btn-create">➕ Create Event</a>
            <a href="{{ route('admin.users') }}" class="btn btn-create">Show Users</a>
            <a href="{{ route('logout') }}" class="btn btn-logout">Logout</a>
        </div>
    </div>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @forelse($events as $event)
    <div class="event-card">
        <div class="event-content">
            <div class="event-info">
                <h3><a href="{{ route('events.show', $event->event_id) }}">{{ $event->event_name }}</a></h3>
                <p>{{ $event->event_details }}</p>
                <p><strong>Time:</strong> {{ $event->event_timestart }} - {{ $event->event_timeend }}</p>
                <p><strong>Date:</strong> {{ $event->event_datestart }} - {{ $event->event_dateend }}</p>
                <p><strong>Location:</strong> {{ $event->event_location }}</p>
                <p><strong>Posted by:</strong> {{ $event->admin_name }}</p>

                <div class="actions">
                    <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-edit">✏️ Edit</a>
                    <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">🗑️ Delete</button>
                    </form>

                    <form action="{{ route('events.interested', $event->event_id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-primary">Interested ({{ $event->interest_count ?? 0 }})</button>
                    </form>

                    <button type="button" class="btn btn-secondary" onclick="toggleComments({{ $event->event_id }})">
                        Comments ({{ $event->comment_count ?? 0 }})
                    </button>
                </div>

                <div id="comments-{{ $event->event_id }}" style="display:none; margin-top:12px;">
                    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
                        @csrf
                        <textarea name="comment_text" placeholder="Write a comment..." required></textarea>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>

            <!-- ✅ Added image display on the right -->
            @if(!empty($event->event_img))
            <div class="event-image">
                <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}">
            </div>
            @endif
        </div>
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
