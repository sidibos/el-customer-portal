<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectBearerTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has('Authorization')) {
            return $next($request);
        }

        // Read HttpOnly cookie set by /portal/login
        $token = $request->cookie('portal_token');

        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}