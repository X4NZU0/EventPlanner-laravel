<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserRegistration;
use App\Models\Event;

class AdminController extends Controller
{
    // Admin dashboard
    public function index()
    {
        $account = session('account');

        // Check if logged in and is admin
        if (!$account || $account['role'] !== 'admin') {
            return redirect()->route('login')->withErrors(['login' => 'Access denied. Admins only.']);
        }

        $totalUsers = UserRegistration::count();
        $totalEvents = Event::count();

        return view('admin.dashboard', compact('account', 'totalUsers', 'totalEvents'));
    }

    // Show all users (optional)
    public function users()
    {
        $account = session('account');

        if (!$account || $account['role'] !== 'admin') {
            return redirect()->route('login')->withErrors(['login' => 'Access denied.']);
        }

        $users = UserRegistration::all();
        return view('admin.users', compact('users'));
    }

    // Optional: show all events
    public function events()
    {
        $account = session('account');

        if (!$account || $account['role'] !== 'admin') {
            return redirect()->route('login')->withErrors(['login' => 'Access denied.']);
        }

        $events = Event::all();
        return view('admin.events', compact('events'));
    }
}
