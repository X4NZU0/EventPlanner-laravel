<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('framework/maintenance.php'))) {
            return response('Be right back!', 503);
        }

        return $next($request);
    }
}