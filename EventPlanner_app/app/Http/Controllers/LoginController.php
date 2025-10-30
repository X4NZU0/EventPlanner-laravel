<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\UserRegistration;
use App\Models\Admin;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login for both admin and regular users.
     */
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        $email = $request->input('user_email');
        $password = $request->input('user_password');

        // --- Admin Login ---
        $admin = Admin::where('admin_email', $email)->first();
        if ($admin && Hash::check($password, $admin->admin_password)) {
            Session::put('account', [
                'id' => $admin->admin_id,
                'name' => $admin->admin_name,
                'email' => $admin->admin_email,
                'role' => 'admin',
            ]);

            return redirect()->route('events.index')->with('success', 'Logged in as admin.');
        }

        // --- Regular User Login ---
        $user = UserRegistration::where('user_email', $email)->first();
        if ($user && Hash::check($password, $user->user_password)) {
            Session::put('account', [
                'id' => $user->user_id,
                'name' => $user->user_name,
                'email' => $user->user_email,
                'role' => 'user',
                'year' => $user->user_year_lvl,
                'pfp' => $user->user_pfp,
            ]);

            return redirect()->route('events.index')->with('success', 'Logged in successfully.');
        }

        // --- Failed login ---
        return back()->withErrors(['login' => 'Invalid email or password.']);
    }

    /**
     * Logout both admin and regular users.
     */
    public function logout()
    {
        Session::forget('account');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
