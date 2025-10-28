<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    // Trust all proxies
    protected $proxies = '*';

    // Set headers manually (safe fallback)
    protected $headers = Request::HEADER_X_FORWARDED_FOR
                       | Request::HEADER_X_FORWARDED_HOST
                       | Request::HEADER_X_FORWARDED_PORT
                       | Request::HEADER_X_FORWARDED_PROTO;
}
