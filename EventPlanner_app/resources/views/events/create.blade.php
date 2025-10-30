<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="dashboard-page">

<div class="create-event-container">
    <h1>Create New Event</h1>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="create-event-form">
        @csrf

        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" id="event_name" name="event_name" required>
        </div>

        <div class="form-group">
            <label for="event_details">Details</label>
            <textarea id="event_details" name="event_details" required></textarea>
        </div>

        <div class="form-group">
            <label for="event_datestart">Date Start</label>
            <input type="date" id="event_datestart" name="event_datestart" required>
        </div>

        <div class="form-group">
            <label for="event_dateend">Date End</label>
            <input type="date" id="event_dateend" name="event_dateend" required>
        </div>

        <div class="form-group">
            <label for="event_timestart">Time Start</label>
            <input type="time" id="event_timestart" name="event_timestart" required>
        </div>

        <div class="form-group">
            <label for="event_timeend">Time End</label>
            <input type="time" id="event_timeend" name="event_timeend" required>
        </div>

        <div class="form-group">
            <label for="event_location">Location</label>
            <input type="text" id="event_location" name="event_location" required>
        </div>

        <div class="form-group">
            <label for="event_img">Event Image</label>
            <input type="file" id="event_img" name="event_img" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="image-preview" id="imagePreview">
            <p>No image selected yet</p>
        </div>

        <button type="submit" class="btn btn-create">Create Event</button>
        <a href="{{ route('events.index') }}" class="btn btn-logout">Cancel</a>
    </form>
</div>

<script>
function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.innerHTML = '';

    const file = event.target.files[0];
    if (!file) {
        imagePreview.innerHTML = '<p>No image selected yet</p>';
        return;
    }

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.onload = function() {
        URL.revokeObjectURL(img.src);
    };

    imagePreview.appendChild(img);
}
</script>

</body>
</html>