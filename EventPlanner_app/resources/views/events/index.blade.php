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
    $account = session('account');

    $isLoggedIn = !empty($account);
    $isAdmin = isset($account['role']) && $account['role'] === 'admin';
    $displayName = $account['name'] ?? 'Guest';
    $userYear = $account['year'] ?? '';
    $userPfp = $account['pfp'] ?? null;
    $avatarName = $isAdmin ? 'Admin' : $displayName;

    // Avatar color generator
    $words = explode(' ', $avatarName);
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    $colors = ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899'];
    $colorIndex = abs(crc32($avatarName)) % count($colors);
    $bgColor = $colors[$colorIndex];
@endphp

<div class="dashboard-header">
    <div class="dashboard-logo">
        <span class="logo-ev">EV</span><span class="logo-plan">PLAN</span>
    </div>

    <a href="{{ route('profile.edit') }}" class="user-profile-link">
        <div class="user-profile">
            <div class="user-avatar">
                <img src="{{ $userPfp ? asset('storage/' . $userPfp) : asset('images/default-pfp.png') }}" alt="Profile Picture">
            </div>
            <div class="user-info">
                <span class="user-name">{{ $isAdmin ? 'Admin' : $displayName }}</span>
                <span class="user-year">{{ $userYear }}</span>
            </div>
        </div>
    </a>
</div>

<div class="header">
    <h2>All Events</h2>
    <div>
        {{-- ✅ Only Admins See This --}}
        @if($isAdmin)
            <a href="{{ route('events.create') }}" class="btn btn-create">➕ Create Event</a>
            <a href="{{ route('admin.users') }}" class="btn btn-create">Show Users</a>
        @endif

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
                    {{-- ✅ Admin can Edit/Delete --}}
                    @if($isAdmin)
                        <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-edit">✏️ Edit</a>
                        <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" class="delete-event-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete">🗑️ Delete</button>
                        </form>
                    @endif

                    {{-- ✅ Everyone can view/interact --}}
                    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-create">👁️ View Full</a>

                    <form action="{{ route('events.interested', $event->event_id) }}" method="POST" class="interested-form">
                        @csrf
                        <button type="submit" class="btn">Mark Interested ({{ $event->interest_count ?? 0 }})</button>
                    </form>

                    <button type="button" class="btn btn-secondary" onclick="toggleComments({{ $event->event_id }})">
                        Comments ({{ $event->comment_count ?? 0 }})
                    </button>
                </div>

                {{-- ✅ Comment Section --}}
                <div id="comments-{{ $event->event_id }}" style="display:none; margin-top:12px;">
                    <form action="{{ route('events.comment', $event->event_id) }}" method="POST">
                        @csrf
                        <textarea name="event_comment" placeholder="Write a comment..." required></textarea>
                        <button type="submit">Post</button>
                    </form>
                </div>
            </div>

            {{-- ✅ Show event image if available --}}
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

<!-- ===== CUSTOM DELETE MODAL ===== -->
<div id="deleteModal" class="custom-modal hidden">
    <div class="custom-modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this event? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button id="cancelBtn" class="btn-cancel">Cancel</button>
            <button id="confirmDeleteBtn" class="btn-confirm">Delete</button>
        </div>
    </div>
</div>

<script>
const deleteButtons = document.querySelectorAll('.btn-delete');
const modal = document.getElementById('deleteModal');
const cancelBtn = document.getElementById('cancelBtn');
const confirmBtn = document.getElementById('confirmDeleteBtn');
let currentForm = null;

deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        currentForm = btn.closest('form');
        modal.classList.remove('hidden');
    });
});

cancelBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    currentForm = null;
});

confirmBtn.addEventListener('click', () => {
    if (currentForm) currentForm.submit();
});

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.classList.add('hidden');
        currentForm = null;
    }
});
</script>

<style>
.custom-modal {
    position: fixed;
    inset: 0;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.25s ease-in-out;
}
.custom-modal:not(.hidden) { opacity: 1; }

.custom-modal-content {
    background-color: #1f1f1f;
    color: white;
    padding: 2rem;
    border-radius: 1rem;
    width: 90%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.7);
}

.custom-modal-actions {
    margin-top: 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-cancel, .btn-confirm {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
}

.btn-cancel { background-color: #555; }
.btn-cancel:hover { background-color: #777; }

.btn-confirm { background-color: #e74c3c; }
.btn-confirm:hover { background-color: #ff5757; }

.hidden { display: none; }
</style>

</body>
</html>
