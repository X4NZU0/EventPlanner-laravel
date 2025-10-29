<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Event Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-page">

    <!-- NAVBAR / TOP BAR -->
    <header class="dashboard-header">
        <h1 class="dashboard-logo">Event Planner</h1>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name">{{ $user->user_name }}</span>
            </div>
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->user_name) }}&background=4f46e5&color=fff" alt="User Avatar">
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <div class="card">
            <h2>Welcome, {{ $user->user_name }}!</h2>
            <p><strong>Email:</strong> {{ $user->user_email }}</p>
            <p><strong>Year Level:</strong> {{ $user->user_year_lvl ?? 'N/A' }}</p>

            <a href="{{ route('logout') }}" class="btn-logout">Logout</a>
        </div>
    </main>

</body>
</html>

