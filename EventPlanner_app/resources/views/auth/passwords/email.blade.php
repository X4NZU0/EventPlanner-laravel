@extends('layouts.app')

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
</div>
@endsection
