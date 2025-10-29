<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if regular user session exists
        if (!Session::has('user')) {
            // Redirect to login page with an error if not logged in
            return redirect()->route('login')->withErrors([
                'access' => 'You must be logged in to access this page.'
            ]);
        }

        return $next($request);
    }
}
