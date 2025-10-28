<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf
        <label>Student ID:</label>
        <input type="text" name="user_student_id"><br>

        <label>Name:</label>
        <input type="text" name="user_name" required><br>

        <label>Email:</label>
        <input type="email" name="user_email"><br>

        <label>Password:</label>
        <input type="password" name="user_password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="user_password_confirmation" required><br>

        <label>Year Level:</label>
        <input type="text" name="user_year_lvl"><br>

        <label>Contact Number:</label>
        <input type="text" name="user_number"><br>

        <button type="submit">Register</button>
    </form>
    <a href="{{ route('welcome') }}">back</a>
</body>
</html>