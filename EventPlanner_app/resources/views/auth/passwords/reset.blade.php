@extends('layouts.app')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')

<div class="reset-container">
    <h2>Reset Password</h2>
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" placeholder="Your email" required>
        <input type="password" name="password" placeholder="New password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm password" required>
        @error('email') <p class="error">{{ $message }}</p> @enderror
        @error('password') <p class="error">{{ $message }}</p> @enderror
        <button type="submit">Reset Password</button>
    </form>
</div>
@endsection
