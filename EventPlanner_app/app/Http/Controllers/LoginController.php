<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRegistration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        // Find user by email
        $user = UserRegistration::where('user_email', $request->user_email)->first();

        // Check if user exists and password matches
        if ($user && Hash::check($request->user_password, $user->user_password)) {
            // Store user info in session
            Session::put('user', $user);
            return redirect()->route('dashboard');
        }

        // If credentials fail
        return back()->withErrors(['login' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Session::forget('user');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
