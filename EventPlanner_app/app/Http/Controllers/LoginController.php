<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserRegistration;

class LoginController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        $email = $request->user_email;
        $password = $request->user_password;

        // Check admin first
        $admin = DB::table('admin')->where('admin_email', $email)->first();
        if ($admin && Hash::check($password, $admin->admin_password)) {
    Session::put('admin', $admin); // admin session
    return redirect()->route('events.index')->with('success', 'Logged in as admin.');
}


        // Check regular user
        $user = UserRegistration::where('user_email', $email)->first();
        if ($user && Hash::check($password, $user->user_password)) {
    Session::put('user', $user); // user session
    return redirect()->route('events.index')->with('success', 'Logged in successfully.');
}
        // Failed login
        return back()->withErrors(['login' => 'Invalid email or password.']);
    }

    // Logout
    public function logout()
    {
        Session::forget('user');
        Session::forget('admin');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
