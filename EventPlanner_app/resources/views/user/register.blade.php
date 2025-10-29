<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Event Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="register-page">
    <div class="register-container">
        <h2>User Registration</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="register-form">
            @csrf

            <div class="form-group">
                <label for="user_student_id">Student ID</label>
                <input type="text" id="user_student_id" name="user_student_id" value="{{ old('user_student_id') }}">
            </div>

            <div class="form-group">
                <label for="user_name">Name</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>

            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="user_email">
            </div>

            <div class="form-group">
                <label for="user_password">Password</label>
                <input type="password" id="user_password" name="user_password" required>
            </div>

            <div class="form-group">
                <label for="user_password_confirmation">Confirm Password</label>
                <input type="password" id="user_password_confirmation" name="user_password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="user_year_lvl">Year Level</label>
                <input type="text" id="user_year_lvl" name="user_year_lvl">
            </div>

            <div class="form-group">
                <label for="user_number">Contact Number</label>
                <input type="text" id="user_number" name="user_number">
            </div>

            <button type="submit" class="btn-primary full-width">Register</button>
        </form>

        <a href="{{ route('welcome') }}" class="back-link">← Back to Welcome</a>
    </div>
</body>
</html>