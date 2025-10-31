<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\UserRegistration;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Show form to request reset
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Handle sending reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = UserRegistration::where('user_email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Generate reset link
        $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($request->email));

        // Log-based email (for local testing)
        Mail::raw("Click here to reset your password: $resetLink", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset Request');
        });

        return back()->with('status', 'We have emailed your password reset link! (Check your log file)');
    }

    // Show reset form
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    // Handle reset submission
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }

        // Update user's password
        UserRegistration::where('user_email', $request->email)
            ->update(['user_password' => Hash::make($request->password)]);

        // Delete reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Your password has been reset! Please log in.');
    }
}
