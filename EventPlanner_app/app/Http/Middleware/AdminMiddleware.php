<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // Check if admin session exists
        if (Session::get('admin')!='admin') {
            return redirect()->route('login')->withErrors(['access' => 'You must be an admin to access this page.']);
        }

        // Access session as array
        $admin = Session::get('admin');
        $adminId = $admin['admin_id'] ?? null;

        if (!$adminId || !Admin::find($adminId)) {
            Session::forget('admin');
            return redirect()->route('login')->withErrors(['access' => 'Admin account not found.']);
        }

        return $next($request);
    }
}
