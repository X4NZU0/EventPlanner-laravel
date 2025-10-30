<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserRegistration;

class UserController extends Controller
{
    // Show profile edit form
    
    public function edit()
    {
        
        $userSession = session('account');

        if (!$userSession) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        // ✅ Fetch full user record
        $user = UserRegistration::where('user_id', $userSession['id'])->firstOrFail();

        return view('user.profile', compact('user'));
    }

    // Handle profile update
    public function update(Request $request)
    {
        $userSession = session('account');

        if (!$userSession) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        // ✅ Validate only necessary fields
        $request->validate([
            'user_name' => 'required|string|max:100',
            'user_email' => 'required|email',
            'user_number' => 'nullable|string|max:20',
            'user_year_lvl' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'user_pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Get user
        $user = UserRegistration::findOrFail($userSession['id']);

        // ✅ Update only if changed
        if ($user->user_name !== $request->user_name) {
            $user->user_name = $request->user_name;
        }

        if ($user->user_email !== $request->user_email) {
            $user->user_email = $request->user_email;
        }

        if ($user->user_number !== $request->user_number) {
            $user->user_number = $request->user_number;
        }

        if ($user->user_year_lvl !== $request->user_year_lvl) {
            $user->user_year_lvl = $request->user_year_lvl;
        }

        // ✅ Handle profile picture (optional)
        if ($request->hasFile('user_pfp')) {
            // Delete old file if it exists
            if ($user->user_pfp && Storage::disk('public')->exists($user->user_pfp)) {
                Storage::disk('public')->delete($user->user_pfp);
            }

            // Store new one
            $path = $request->file('user_pfp')->store('profile_pictures', 'public');
            $user->user_pfp = $path;
        }

        // ✅ Handle password (only if filled)
        if ($request->filled('password')) {
            $user->user_password = Hash::make($request->password);
        }

        // ✅ Save all updates
        $user->save();

        // ✅ Refresh session (so navbar/profile uses latest info)
        session([
            'user' => [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_pfp' => $user->user_pfp,
            ]
        ]);

        // ✅ Redirect back to profile page
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
