<!DOCTYPE html>
<html>
<head>
  <title>Create Event</title>
  <style>
    body { font-family: Arial; background: #f7f7f7; margin: 20px; }
    .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
    input, textarea { width: 100%; margin: 8px 0; padding: 10px; }
    button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
    a { color: #007bff; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create New Event</h2>

    @if($errors->any())
      <div style="color:red;">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('events.store') }}">
      @csrf
      <label>Event Name:</label>
      <input type="text" name="event_name" required>

      <label>Details:</label>
      <textarea name="event_details" maxlength="150" required></textarea>

      <label>Location:</label>
      <input type="text" name="event_location" required>

      <label>Time:Start</label>
      <input type="time" name="event_timestart" required>

      <label>Time:End</label>
      <input type="time" name="event_timeend" required>

      <label>Date:Start</label>
      <input type="date" name="event_datestart" required>
      
      <label>Date:End</label>
      <input type="date" name="event_dateend" required>

      <input  type= "submit" value="Create Event">
    </form>

    <p><a href="{{ route('events.index') }}">Back to Events</a></p>
  </div>
</body>
</html>
