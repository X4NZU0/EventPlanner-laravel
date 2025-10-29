<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // 1️⃣ Check in ADMINS table
        $admin = DB::table('admins')->where('admin_email', $email)->first();

        if ($admin && $admin->admin_password === $password) {
            Session::put('role', 'admin');
            Session::put('admin_id', $admin->admin_id);
            Session::put('admin_name', $admin->admin_name);
            return redirect()->route('admin.users');
        }

        // 2️⃣ Check in USERS table
        $user = DB::table('users')->where('user_email', $email)->first();

        if ($user && $user->user_password === $password) {
            Session::put('role', 'user');
            Session::put('user_id', $user->user_id);
            Session::put('user', $user);
            return redirect()->route('dashboard');
        }

        // ❌ Invalid credentials
        return back()->withErrors(['login' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}


