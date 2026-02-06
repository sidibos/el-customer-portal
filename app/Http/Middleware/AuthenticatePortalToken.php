<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticatePortalToken
{
    public function handle(Request $request, Closure $next)
    {
        // If already authenticated, continue
        if (Auth::check()) {
            $token = $request->cookie('portal_token');
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
            return $next($request);
        }

        $token = $request->cookie('portal_token');

        if (!$token) {
            return redirect()->route('login');
        }

        // Sanctum helper to resolve the token model from plain token
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable) {
            return redirect()->route('login');
        }

        // Log user in for this request (session-less is OK; this is per-request)
        Auth::login($accessToken->tokenable);

        return $next($request);
    }
}