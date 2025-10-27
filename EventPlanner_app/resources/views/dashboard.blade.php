<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ $user->user_name }}!</h1>

    <p>Email: {{ $user->user_email }}</p>
    <p>Year Level: {{ $user->user_year_lvl ?? 'N/A' }}</p>

    <a href="{{ route('logout') }}">Logout</a>
</body>
</html>
