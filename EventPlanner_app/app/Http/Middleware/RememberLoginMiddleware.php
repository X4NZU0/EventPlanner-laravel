<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\UserRegistration;
use Illuminate\Support\Facades\Session;

class RememberLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user') && cookie('remember_user')) {
            $user = UserRegistration::find(cookie('remember_user'));
            if ($user) {
                Session::put('user', [
                    'user_id' => $user->user_id,
                    'user_name' => $user->user_name,
                    'user_email' => $user->user_email,
                    'status' => 'user'
                ]);
            }
        }

        if (!Session::has('admin') && cookie('remember_admin')) {
            $admin = Admin::find(cookie('remember_admin'));
            if ($admin) {
                Session::put('admin', [
                    'admin_id' => $admin->admin_id,
                    'admin_name' => $admin->admin_name,
                    'admin_email' => $admin->admin_email,
                    'status' => 'admin'
                ]);
            }
        }

        return $next($request);
    }
}
