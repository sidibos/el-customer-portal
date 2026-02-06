<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class PortalTokenAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        // Create personal access token (portal)
        $plainToken = $user->createToken('portal')->plainTextToken;

        // Set token in an HttpOnly cookie so full page loads remain authenticated
        return response()->json([
            'message' => 'Logged in',
        ])->cookie(
            cookie(
                name: 'portal_token',
                value: $plainToken,
                minutes: 60 * 24 * 7, // 7 days
                path: '/',
                domain: null,
                secure: false,  // set true in HTTPS
                httpOnly: true,
                raw: false,
                sameSite: 'lax'
            )
        );
    }

    public function logout(Request $request)
    {
        $token = $request->cookie('portal_token');

        // delete token server-side if we can
        if ($token) {
            $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            $accessToken?->delete();
        }

        // clear cookie
        return response()->json(['message' => 'Logged out'])
            ->withoutCookie('portal_token');
    }
}