<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <h1>Welcome, {{ $user->user_name }}!</h1>
    <p>Email: {{ $user->user_email }}</p>
    <p>Year Level: {{ $user->user_year_lvl ?? 'N/A' }}</p>

    <div class="events">
        @foreach ($events as $event)
            <div class="event-card">
                <div class="event-info">
                    <h2>{{ $event->event_name }}</h2>
                    <p>{{ $event->event_details }}</p>
                    <p><strong>Location:</strong> {{ $event->event_location }}</p>
                    <p><strong>Date:</strong> {{ $event->event_datestart }} → {{ $event->event_dateend }}</p>
                </div>

                @if(!empty($event->event_img))
                    <div class="event-img">
                        <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}">
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>

