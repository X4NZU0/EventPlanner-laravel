<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin')) {
            return redirect('/login')->withErrors(['access' => 'Admin access required.']);
        }
        return $next($request);
    }
}
