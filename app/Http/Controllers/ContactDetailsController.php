<?php

namespace App\Http\Controllers;

use App\Contracts\UserServiceInterface;
use App\Http\Requests\UpdateContactDetailsRequest;
use Illuminate\Http\Request;

class ContactDetailsController extends Controller
{
    public function show(Request $request, UserServiceInterface $userService)
    {
        return response()->json($userService->contactDetails($request->user()));
    }

    public function update(UpdateContactDetailsRequest $request, UserServiceInterface $userService)
    {
        $data = $userService->updateContactDetails(
            $request->user(),
            $request->validated('email'),
            $request->validated('phone')
        );

        return response()->json([
            'message' => 'Contact details updated',
            ...$data,
        ]);
    }
}