<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Session::has('user_id') && !Session::has('admin_id')) {
            return redirect('/login')->withErrors(['access' => 'You must log in first.']);
        }

        return $next($request);
    }
}
