<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\UserServiceInterface;

class UserController extends Controller
{
    public function show(Request $request, UserServiceInterface $usersService)
    {
        return response()->json($usersService->context($request->user()));
    }
}
