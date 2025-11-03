@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')

<div class="reset-container">
    <h2>Forgot Password</h2>
    @if(session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required>
        @error('email') <p class="error">{{ $message }}</p> @enderror
        <button type="submit">Send Reset Link</button>
    </form>

      
    <p style="margin-top: 1rem; font-size: 0.9rem; color: #aaa;">
        Remembered your password?
        <a href="{{ route('login') }}" style="color: #60a5fa; text-decoration: none;">Go back to login</a>
    </p>


</div>
@endsection
