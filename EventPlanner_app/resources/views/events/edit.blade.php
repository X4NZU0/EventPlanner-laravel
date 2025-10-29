@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Event</h2>

    {{-- Display any validation errors --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit event form --}}
    <form action="{{ route('events.update', $event->event_id) }}" method="POST">
        @csrf
        <div style="margin-bottom: 10px;">
            <label>Event Name:</label><br>
            <input type="text" name="event_name" value="{{ old('event_name', $event->event_name) }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Details:</label><br>
            <textarea name="event_details" rows="3" required>{{ old('event_details', $event->event_details) }}</textarea>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Location:</label><br>
            <input type="text" name="event_location" value="{{ old('event_location', $event->event_location) }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Start Date:</label><br>
            <input type="date" name="event_datestart" value="{{ old('event_datestart', $event->event_datestart) }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>End Date:</label><br>
            <input type="date" name="event_dateend" value="{{ old('event_dateend', $event->event_dateend) }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Start Time:</label><br>
            <input type="time" name="event_timestart" value="{{ old('event_timestart', $event->event_timestart) }}" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>End Time:</label><br>
            <input type="time" name="event_timeend" value="{{ old('event_timeend', $event->event_timeend) }}" required>
        </div>

        <button type="submit">Update Event</button>
    </form>

    <hr style="margin: 20px 0;">

    {{-- Delete event --}}
    <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="background-color: crimson; color: white;">Delete Event</button>
    </form>

    <br>
    <a href="{{ route('events.index') }}">← Back to Events</a>
</div>
@endsection