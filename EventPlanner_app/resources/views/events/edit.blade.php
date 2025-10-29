@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')

<div class="event-editor-page">
    <div class="container mt-5">
        <h2 class="mb-4">Edit Event</h2>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Edit Event Form --}}
        <form action="{{ route('events.update', $event->event_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Event Name</label>
                <input type="text" name="event_name" class="form-control" 
                       value="{{ old('event_name', $event->event_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Details</label>
                <textarea name="event_details" class="form-control" rows="3" required>{{ old('event_details', $event->event_details) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="event_location" class="form-control"
                       value="{{ old('event_location', $event->event_location) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="event_datestart" class="form-control"
                       value="{{ old('event_datestart', $event->event_datestart) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="event_dateend" class="form-control"
                       value="{{ old('event_dateend', $event->event_dateend) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Time</label>
                <input type="time" name="event_timestart" class="form-control"
                       value="{{ old('event_timestart', $event->event_timestart) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">End Time</label>
                <input type="time" name="event_timeend" class="form-control"
                       value="{{ old('event_timeend', $event->event_timeend) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection