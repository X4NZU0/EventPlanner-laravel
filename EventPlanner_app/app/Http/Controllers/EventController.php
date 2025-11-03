<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'event_details' => 'required|max:1000',
            'event_location' => 'required',
            'event_timestart' => 'required',
            'event_timeend' => 'required',
            'event_datestart' => 'required|date',
            'event_dateend' => 'required|date',
            'event_img' => 'nullable|image|max:2048',
        ]);

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
            'event_poster' => $account['id'],
            'event_img' => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $account = session('account');

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

        if (!$account || $account['role'] !== 'admin') {
            return redirect()->route('events.index')->withErrors(['access' => 'Only admins can update events.']);
        }

        $request->validate([
            'event_name' => 'required',
            'event_details' => 'required|max:1000',
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

        if (!$account || $account['role'] !== 'admin') {
            return redirect()->route('events.index')->withErrors(['access' => 'Only admins can delete events.']);
        }

        DB::table('event_comments')->where('event_id', $id)->delete();
        DB::table('event')->where('event_id', $id)->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

    // Mark interested
    public function interested($eventId)
    {
        $account = session('account');

        if (!$account) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        $isAdmin = ($account['role'] === 'admin');
        $actorId = $account['id'];

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

        if ($exists) {
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
    $account = session('account');
    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $userId = null;
    $adminId = null;

    if ($account['role'] === 'admin') {
        $exists = DB::table('admin')->where('admin_id', $account['id'])->exists();
        if (!$exists) return back()->withErrors(['account' => 'Admin account not found.']);
        $adminId = $account['id'];
    } else {
        $exists = DB::table('user_registration')->where('user_id', $account['id'])->exists();
        if (!$exists) return back()->withErrors(['account' => 'User account not found.']);
        $userId = $account['id'];
    }

    // Optional: verify parent_id exists
    $parentId = $request->input('parent_id');
    if ($parentId) {
        $parentExists = DB::table('event_comments')->where('id', $parentId)->exists();
        if (!$parentExists) $parentId = null;
    }

    DB::table('event_comments')->insert([
        'event_id' => $eventId,
        'user_id' => $userId,
        'admin_id' => $adminId,
        'parent_id' => $parentId,
        'event_comment' => $request->input('event_comment'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Comment added!');
}



    public function likeComment($eventId, $commentId)
{
    $account = session('account');
    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $comment = DB::table('event_comments')->where('id', $commentId)->first();
    if (!$comment) {
        return back()->withErrors(['notfound' => 'Comment not found.']);
    }

    // Prevent double-like by the same user/admin (optional, for simplicity we skip tracking)
    DB::table('event_comments')->where('id', $commentId)->increment('likes');

    return back();
}
public function dislikeComment($eventId, $commentId)
{
    $account = session('account');
    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $comment = DB::table('event_comments')->where('id', $commentId)->first();
    if (!$comment) {
        return back()->withErrors(['notfound' => 'Comment not found.']);
    }

    DB::table('event_comments')->where('id', $commentId)->increment('dislikes');

    return back();
}


    // Show event details
    public function show($id)
    {
        $account = session('account');

        if (!$account) {
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
        ->orderBy('event_comments.created_at', 'asc')
        ->get();
}

    public function deleteComment($eventId, $commentId)
{
    $account = session('account');

    if (!$account) {
        return redirect('/login')->withErrors(['access' => 'You must log in first.']);
    }

    $comment = DB::table('event_comments')->where('id', $commentId)->first();
    if (!$comment) {
        return back()->withErrors(['notfound' => 'Comment not found.']);
    }

    // Only allow the owner (user or admin) to delete
    if (($account['role'] === 'user' && $account['id'] == $comment->user_id) ||
        ($account['role'] === 'admin' && $account['id'] == $comment->admin_id)) {
        DB::table('event_comments')->where('id', $commentId)->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }

    return back()->withErrors(['access' => 'You cannot delete this comment.']);
}

}

