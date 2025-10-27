<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Event Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">
    <div class="login-container">
        <h2>LOGIN</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="user_email" value="{{ old('user_email') }}" required>
            </div>

<div class="form-group">
    <label for="user_password">Password</label>
    <div class="password-wrapper">
        <input type="password" id="user_password" name="user_password" required>

        <!-- Eye icon (default hidden) -->
        <span class="toggle-password" onclick="togglePassword()">
            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>

            <!-- Eye-off icon (hidden by default) -->
            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a10.056 10.056 0 012.307-3.801M6.688 6.688A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.958 9.958 0 01-1.537 2.564M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
            </svg>
        </span>
    </div>
</div>


            <button type="submit" class="btn-primary full-width">Login</button>
        </form>

        <p class="auth-note">
            Don’t have an account?
            <a href="{{ route('register') }}" class="link">Register here</a>
        </p>
    </div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('user_password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if(passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'inline';
    } else {
        passwordInput.type = 'password';
        eyeOpen.style.display = 'inline';
        eyeClosed.style.display = 'none';
    }
}
</script>