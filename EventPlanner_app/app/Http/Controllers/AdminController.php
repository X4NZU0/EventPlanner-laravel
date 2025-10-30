<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Assuming you manage users
use App\Models\Event; // If you want to show event stats
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Admin dashboard
    public function index()
{
    $user = session('user');
    
    // Check if logged in and is admin (roles == 2)
    if (!$user || ($user['roles'] ?? 1) != 2) {
        return redirect()->route('login')->withErrors(['login' => 'Access denied. Admins only.']);
    }

    $totalUsers = User::count();
    $totalEvents = Event::count();

    return view('admin.dashboard', compact('user', 'totalUsers', 'totalEvents'));
}


    // Show all users (optional, can be separate from UserManagementController)
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Optional: show all events
    public function events()
    {
        $events = Event::all();
        return view('admin.events', compact('events'));
    }
}
