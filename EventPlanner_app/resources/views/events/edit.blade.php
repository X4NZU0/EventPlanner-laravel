@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')

<div class="event-editor-page">
    <div class="container mt-5">
        <h2 class="mb-4">Edit Event</h2>

        {{-- Validation errors --}}
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
        <form action="{{ route('events.update', $event->event_id) }}" method="POST" enctype="multipart/form-data">
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

            {{-- Image upload --}}
            <div class="mb-4">
                <label class="form-label">Event Image</label>
                <input type="file" name="event_img" accept="image/*" class="form-control" onchange="previewImage(event)">
            </div>

            {{-- Image Preview Section --}}
        <div class="image-preview-wrapper">
            <div class="current-image">
                <div class="image-label">Current Image</div>
                @if(!empty($event->event_img))
                    <img src="{{ asset('storage/' . $event->event_img) }}" alt="Current Image">
                @else
                    <p>No image</p>
                @endif
            </div>

            <div class="arrow-middle">→</div>

            <div class="new-image">
                <div class="image-label">New Image Preview</div>
                <img id="imagePreview" src="#" alt="New Image" style="display: none;">
            </div>
        </div>


            <button type="submit" class="btn btn-primary mt-4">Update Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary mt-4 ms-2">Cancel</a>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if(file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
</script>

@endsection
