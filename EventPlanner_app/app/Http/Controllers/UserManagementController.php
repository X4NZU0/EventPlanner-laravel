<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    // Show list of users
    public function index()
    {
        $users = DB::table('user')->orderBy('user_id', 'asc')->get();
        return view('admin.users', compact('users'));
    }

    // Change user role (1 = user, 2 = admin)
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|integer|in:1,2',
        ]);

        DB::table('user')->where('user_id', $id)->update([
            'roles' => $request->roles,
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        DB::table('user')->where('user_id', $id)->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}