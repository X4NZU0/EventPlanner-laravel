@extends('layouts.app')

@section('content')
<div class="profile-container">
    <h2>Edit Profile</h2>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; background: #2e7d32; padding: 0.75rem; border-radius: 0.5rem; color: #fff;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- ✅ Profile Picture Section -->
        <div class="profile-picture-section" style="text-align: center; margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: #ccc;"><strong>Profile Picture</strong></label>

            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <img 
                    id="preview"
                    src="{{ $user->user_pfp ? asset('storage/' . $user->user_pfp) . '?v=' . now()->timestamp : asset('images/default-pfp.png') }}"
                    alt="Profile Picture"
                    width="120" height="120"
                    style="border-radius: 50%; object-fit: cover; box-shadow: 0 0 10px rgba(0,0,0,0.4);"
                >

                <input 
                    type="file" 
                    name="user_pfp" 
                    id="user_pfp" 
                    accept="image/*"
                    onchange="previewImage(event)"
                    style="margin-top: 0.5rem; background: none; color: #ccc;"
                >
            </div>
        </div>

        <!-- ✅ Profile Information -->
        <div class="form-group">
            <label for="user_name">Full Name</label>
            <input type="text" name="user_name" id="user_name"
                   value="{{ old('user_name', $user->user_name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="user_email" id="email"
                   value="{{ old('user_email', $user->user_email) }}" required>
        </div>

        <div class="form-group">
            <label for="user_number">Contact Number</label>
            <input type="text" name="user_number" id="user_number"
                   value="{{ old('user_number', $user->user_number) }}">
        </div>

        <div class="form-group">
            <label for="user_year_lvl">Year Level</label>
            <input type="text" name="user_year_lvl" id="user_year_lvl"
                   value="{{ old('user_year_lvl', $user->user_year_lvl) }}">
        </div>

        <!-- ✅ Password Update -->
        <div class="form-group">
            <label for="password">New Password (optional)</label>
            <input type="password" name="password" id="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

        <button type="submit" class="btn-update">Update Profile</button>
    </form>

    <a href="{{ route('events.index') }}" class="back-link">← Back to Events</a>
</div>

<!-- ✅ Live Image Preview -->
<script>
function previewImage(event) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('preview').src = reader.result;
    };
    reader.readAsDataURL(file);
}

// 👁️ Toggle Password Visibility
document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }
});
</script>
@endsection
