<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="welcome-page">
    <div class="welcome-container">
      <h1>Event Planner</h1>
      <p>
        Manage and organize your events effortlessly. <br />
        Create schedules, track participants, and make every event unforgettable.
      </p>

      <div class="welcome-buttons">
        <a href="{{ route('register') }}" class="btn-primary">Register</a>
        <a href="{{ route('login') }}" class="btn-secondary">Login</a>
      </div>
    </div>
  </body>
</html>