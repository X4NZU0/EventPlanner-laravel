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

        // Fetch full user record
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

        // Validate input
        $request->validate([
            'user_name' => 'required|string|max:100',
            'user_email' => 'required|email',
            'user_number' => 'nullable|string|max:20',
            'user_year_lvl' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'user_pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get user
        $user = UserRegistration::findOrFail($userSession['id']);

        // Update fields if changed
        $user->user_name = $request->user_name;
        $user->user_email = $request->user_email;
        $user->user_number = $request->user_number;
        $user->user_year_lvl = $request->user_year_lvl;

        // Handle profile picture
        if ($request->hasFile('user_pfp')) {
            $file = $request->file('user_pfp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pfps', $filename, 'public');

            // Optionally delete old picture
            if ($user->user_pfp && Storage::disk('public')->exists($user->user_pfp)) {
                Storage::disk('public')->delete($user->user_pfp);
            }

            $user->user_pfp = $path;
        }

        // Handle password if filled
        if ($request->filled('password')) {
            $user->user_password = Hash::make($request->password);
        }

        // Save all updates
        $user->save();

        // Update session to reflect latest changes
        session(['account' => [
            'id' => $user->user_id,
            'name' => $user->user_name,
            'email' => $user->user_email,
            'pfp' => $user->user_pfp,
            'year' => $user->user_year_lvl
        ]]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
