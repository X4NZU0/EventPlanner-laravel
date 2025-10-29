<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;

class EventController extends Controller
{
    // Show all events
    public function index()
    {
        $userOrAdmin = session('user') ?? session('admin');
        if (!$userOrAdmin) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        $events = DB::table('event')
    ->leftJoin('admin', 'event.event_poster', '=', 'admin.admin_id')
    ->select(
        'event.*',
        'admin.admin_name',
        DB::raw('(SELECT COUNT(*) FROM event_interaction WHERE event_id = event.event_id) AS interest_count'),
        DB::raw('(SELECT COUNT(*) FROM event_comments WHERE event_id = event.event_id) AS comment_count')
    )
    ->orderBy('event_datestart', 'desc')
    ->get();


        return view('events.index', compact('events', 'userOrAdmin'));
    }

    // Show event creation form (admins only)
    public function create()
{
    if (!session()->has('admin')) {
        return redirect('/login')->withErrors(['access' => 'Admin access required.']);
    }
    return view('events.create');
}


    // Store a new event (admins only)
    public function store(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/login')->withErrors(['access' => 'Admin access required.']);
        }

        $request->validate([
            'event_name' => 'required',
            'event_details' => 'required|max:150',
            'event_location' => 'required',
            'event_timestart' => 'required',
            'event_timeend' => 'required',
            'event_datestart' => 'required|date',
            'event_dateend' => 'required|date',
        ]);

        DB::table('event')->insert([
            'event_name' => $request->event_name,
            'event_details' => $request->event_details,
            'event_location' => $request->event_location,
            'event_datestart' => $request->event_datestart,
            'event_dateend' => $request->event_dateend,
            'event_timestart' => $request->event_timestart,
            'event_timeend' => $request->event_timeend,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    // Show edit form (admins only)
   public function edit($id)
{
    // Check if user or admin is logged in
    if (!Session::has('user_id') && !Session::has('admin_id')) {
        return redirect()->route('login')->withErrors(['access' => 'You must log in first.']);
    }

    // Get the event safely
    $event = DB::table('event')->where('event_id', $id)->first();

    if (!$event) {
        return redirect()->route('events.index')->withErrors(['notfound' => 'Event not found.']);
    }

    // Optionally get session status (if used for display logic)
    $status = Session::get('status');

    return view('events.edit', compact('event', 'status'));
}



    // Update event (admins only)
    public function update(Request $request, $id)
    {
        if (!session()->has('admin')) {
            return redirect('/login')->withErrors(['access' => 'Admin access required.']);
        }

        $request->validate([
            'event_name' => 'required',
            'event_details' => 'required|max:150',
            'event_location' => 'required',
            'event_timestart' => 'required',
            'event_timeend' => 'required',
            'event_datestart' => 'required|date',
            'event_dateend' => 'required|date',
        ]);

        DB::table('event')->where('event_id', $id)->update([
            'event_name' => $request->event_name,
            'event_details' => $request->event_details,
            'event_location' => $request->event_location,
            'event_datestart' => $request->event_datestart,
            'event_dateend' => $request->event_dateend,
            'event_timestart' => $request->event_timestart,
            'event_timeend' => $request->event_timeend,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    // Delete event (admins only)
    public function destroy($id)
    {
        if (!session()->has('admin')) {
            return redirect('/login')->withErrors(['access' => 'Admin access required.']);
        }

        DB::table('event')->where('event_id', $id)->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    // Mark interested (users + admins)
    public function markInterested($id)
    {
        $userOrAdmin = session('user') ?? session('admin');
        if (!$userOrAdmin) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        $userId = $userOrAdmin['user_id'] ?? null; // if admin, skip marking
        if (!$userId) {
            return back()->with('info', 'Admins do not need to mark interest.');
        }

        $existing = DB::table('event_interaction')->where('event_id', $id)->where('user_id', $userId)->first();

        if (!$existing) {
            DB::table('event_interaction')->insert([
                'event_id' => $id,
                'user_id' => $userId,
            ]);
        }

        return back()->with('success', 'You marked this event as interested.');
    }

    // Show event details (users + admins)
    public function show($id)
    {
        $userOrAdmin = session('user') ?? session('admin');
        if (!$userOrAdmin) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        $event = DB::table('event')->where('event_id', $id)->first();
        if (!$event) {
            return redirect()->route('events.index')->withErrors(['event' => 'Event not found.']);
        }

        $interestCount = DB::table('event_interaction')->where('event_id', $id)->count();
        $commentCount = DB::table('event_comments')->where('event_id', $id)->count();

        return view('events.show', compact('event', 'interestCount', 'commentCount'));
    }

    // Add comment (users + admins)
    public function addComment(Request $request, $id)
    {
        $userOrAdmin = session('user') ?? session('admin');
        if (!$userOrAdmin) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        $userId = $userOrAdmin['user_id'] ?? null;
        if (!$userId) {
            return back()->with('info', 'Admins cannot comment.');
        }

        $request->validate([
            'comment_text' => 'required|string|max:150',
        ]);

        DB::table('event_comments')->insert([
            'event_id' => $id,
            'event_comment' => $request->comment_text,
            'comment_datetime' => now(),
        ]);

        return back()->with('success', 'Comment added successfully.');
    }
}
