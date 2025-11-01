<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class EventController extends Controller
{
    // Show all events
   public function index()
{
    $account = session('account');

    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $userPfp = $account['pfp'] ?? null;
    $displayName = $account['name'] ?? 'Guest';
    $userYear = $account['year'] ?? '-';
    $isAdmin = ($account['role'] ?? '') === 'admin';

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

    return view('events.index', compact('events', 'userPfp', 'displayName', 'userYear', 'isAdmin'));
}



    // Show event creation form (admins only)
    // Show event creation form (admins only)
public function create()
{
    $account = session('account');

    if (!$account || ($account['role'] ?? '') !== 'admin') {
        return redirect('/login')->withErrors(['access' => 'Admin access required.']);
    }

    return view('events.create');
}

public function store(Request $request)
{
    $account = session('account');

    if (!$account || ($account['role'] ?? '') !== 'admin') {
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
        'event_img' => 'nullable|image|max:2048',
    ]);

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('event_img')) {
        $imagePath = $request->file('event_img')->store('events', 'public');
    }

    DB::table('event')->insert([
        'event_name' => $request->event_name,
        'event_details' => $request->event_details,
        'event_location' => $request->event_location,
        'event_datestart' => $request->event_datestart,
        'event_dateend' => $request->event_dateend,
        'event_timestart' => $request->event_timestart,
        'event_timeend' => $request->event_timeend,
        'event_poster' => $account['id'], // ✅ fixed: pulls from session('account')
        'event_img' => $imagePath,
    ]);

    return redirect()->route('events.index')->with('success', 'Event created successfully!');
}


    // Show edit form
    public function edit($id)
{
    $account = session('account');

    // ✅ Check if admin
    if (!$account || $account['role'] !== 'admin') {
        return redirect()->route('events.index')->withErrors(['access' => 'Only admins can edit events.']);
    }

    $event = DB::table('event')->where('event_id', $id)->first();
    if (!$event) {
        return redirect()->route('events.index')->withErrors(['notfound' => 'Event not found.']);
    }

    return view('events.edit', compact('event'));
}

public function update(Request $request, $id)
{
    $account = session('account');

    // ✅ Check if admin
    if (!$account || $account['role'] !== 'admin') {
        return redirect()->route('events.index')->withErrors(['access' => 'Only admins can update events.']);
    }

    // Validate input
    $request->validate([
        'event_name' => 'required',
        'event_details' => 'required|max:150',
        'event_location' => 'required',
        'event_timestart' => 'required',
        'event_timeend' => 'required',
        'event_datestart' => 'required|date',
        'event_dateend' => 'required|date',
        'event_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    $updateData = [
        'event_name' => $request->event_name,
        'event_details' => $request->event_details,
        'event_location' => $request->event_location,
        'event_datestart' => $request->event_datestart,
        'event_dateend' => $request->event_dateend,
        'event_timestart' => $request->event_timestart,
        'event_timeend' => $request->event_timeend,
    ];

    // Handle image upload
    if ($request->hasFile('event_img')) {
        $file = $request->file('event_img');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('events', $filename, 'public');

        $updateData['event_img'] = $path;
    }

    DB::table('event')->where('event_id', $id)->update($updateData);

    return redirect()->route('events.index')->with('success', 'Event updated successfully!');
}

public function destroy($id)
{
    $account = session('account');

    // ✅ Check if admin
    if (!$account || $account['role'] !== 'admin') {
        return redirect()->route('events.index')->withErrors(['access' => 'Only admins can delete events.']);
    }

    // Delete event and its comments
    DB::table('event_comments')->where('event_id', $id)->delete();
    DB::table('event')->where('event_id', $id)->delete();

    return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
}

    // Mark interested (users or admins)
    public function interested($eventId)
{
    $account = session('account');

    // Ensure user is logged in
    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $isAdmin = ($account['role'] === 'admin');
    $actorId = $account['id'];

    // Check if already marked as interested
    $exists = DB::table('event_interaction')
        ->where('event_id', $eventId)
        ->where(function ($query) use ($actorId, $isAdmin) {
            if ($isAdmin) {
                $query->where('admin_id', $actorId);
            } else {
                $query->where('user_id', $actorId);
            }
        })
        ->exists();

    // ✅ Toggle logic
    if ($exists) {
        // If already marked, unmark (delete)
        DB::table('event_interaction')
            ->where('event_id', $eventId)
            ->where(function ($query) use ($actorId, $isAdmin) {
                if ($isAdmin) {
                    $query->where('admin_id', $actorId);
                } else {
                    $query->where('user_id', $actorId);
                }
            })
            ->delete();
    } else {
        // If not marked, insert new record
        DB::table('event_interaction')->insert([
            'event_id' => $eventId,
            'user_id' => $isAdmin ? null : $actorId,
            'admin_id' => $isAdmin ? $actorId : null,
            'is_admin' => $isAdmin,
            'created_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', $exists ? 'Removed from interested list.' : 'Marked as interested!');
}






    // Add comment
    public function comment(Request $request, $eventId)
    {
        $userOrAdmin = session('user') ?? session('admin');
        

        $request->validate([
            'event_comment' => 'required|string|max:500',
        ]);

        $userId = session('user_id');
        $adminId = session('admin_id');

        DB::table('event_comments')->insert([
            'event_id' => $eventId,
            'user_id' => $userId,
            'admin_id' => $adminId,
            'user_poster' => session('user_name') ?? 'Anonymous',
            'event_comment' => $request->event_comment,
            'comment_datetime' => now(),
        ]);

        return back()->with('success', 'Comment added!');
    }

    // Show event details
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
        $comments = $this->getComments($id);

        return view('events.show', compact('event', 'interestCount', 'commentCount', 'comments'));
    }

    // Helper: get all comments for event
    private function getComments($eventId)
    {
        return DB::table('event_comments')
            ->leftJoin('user_registration', 'event_comments.user_id', '=', 'user_registration.user_id')
            ->leftJoin('admin', 'event_comments.admin_id', '=', 'admin.admin_id')
            ->select(
                'event_comments.*',
                DB::raw('COALESCE(user_registration.user_name, admin.admin_name) as commenter_name')
            )
            ->where('event_comments.event_id', $eventId)
            ->orderBy('event_comments.comment_datetime', 'desc')
            ->get();
    }
}