<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $account = Session::get('account');

        if (!$account || ($account['role'] ?? null) !== 'superadmin') {
            return redirect()->route('login')->withErrors([
                'access' => 'You must be a superadmin to access this page.'
            ]);
        }

        return $next($request);
    }
}
