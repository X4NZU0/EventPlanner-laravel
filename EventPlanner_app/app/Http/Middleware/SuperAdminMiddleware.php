<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Allow only super admins.
     */
    public function handle(Request $request, Closure $next)
    {
        // session key used in your LoginController is 'account'
        $account = session('account');

        // adjust this check to whatever you set in session for super admin
        // e.g. role might be 'superadmin' or 'admin' or numeric role id
        if (!$account || ($account['role'] ?? '') !== 'superadmin') {
            return redirect()->route('login')->withErrors(['login' => 'Access denied. Super admin only.']);
        }

        return $next($request);
    }
}
