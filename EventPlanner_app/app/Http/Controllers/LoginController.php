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
        // Validate input
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        $email = $request->input('user_email');
        $password = $request->input('user_password');

        // Check admin first
        $admin = Admin::where('admin_email', $email)->first();
        if ($admin && Hash::check($password, $admin->admin_password)) {
            Session::put( [
                'admin_id' => $admin->admin_id,
                'admin_name' => $admin->admin_name,
                'admin_email' => $admin->admin_email,
                'status'=>'admin',
                'admin'=>true
            ]);

            return redirect()->route('events.index')->with('success', 'Logged in as admin.');
        }

        // Check regular user
        $user = UserRegistration::where('user_email', $email)->first();
        if ($user && Hash::check($password, $user->user_password)) {
            Session::put('user', [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'admin'=>true
            ]);

            return redirect()->route('events.index')->with('success', 'Logged in successfully.');
        }

        // Failed login
        return back()->withErrors(['login' => 'Invalid email or password.']);
    }

    /**
     * Logout both admin and regular users.
     */
    public function logout()
    {
        Session::forget('user');
        Session::forget('admin');

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
