<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken
{
    protected $except = [];

    public function handle(Request $request, Closure $next)
    {
        // For simplicity, skip CSRF check for now (not recommended for production)
        return $next($request);
    }
}