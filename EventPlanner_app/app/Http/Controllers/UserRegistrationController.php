<?php

namespace App\Http\Controllers;

use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('user.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
{
    $request->validate([
        'user_student_id' => 'nullable|string|max:45',
        'user_name' => 'required|string|max:45',
        'user_email' => 'required|email|max:45|unique:user_registration,user_email',
        'user_password' => 'required|string|min:8|confirmed',
        'user_year_lvl' => 'nullable|string|max:45',
        'user_number' => 'nullable|string|max:45',
    ]);

    // Save to registration table
    $registration = UserRegistration::create([
        'user_student_id' => $request->user_student_id,
        'user_name' => $request->user_name,
        'user_email' => $request->user_email,
        'user_password' => Hash::make($request->user_password),
        'user_year_lvl' => $request->user_year_lvl,
        'user_number' => $request->user_number,
    ]);

    // Automatically add to `user` table
    DB::table('user')->insert([
        'user_student_id' => $registration->user_student_id,
        'user_name' => $registration->user_name,
        'user_pfp' => null,
        'user_password' => $registration->user_password,
        'user_email' => $registration->user_email,
        'user_year_lvl' => $registration->user_year_lvl,
        'user_number' => $registration->user_number,
        'friend_one' => 0,
        'friend_two' => '0',
        'user_registration_user_id' => $registration->user_id,
        'roles' => 1, // 1 = user, 2 = admin (you can change later)
    ]);

    return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');
}
}