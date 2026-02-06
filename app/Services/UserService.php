<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Exceptions\UserServiceException;
use App\Models\User;
use Throwable;

class UserService implements UserServiceInterface
{
    public function context(User $user): array
    {
        try {
            $user->loadMissing('customer');

            return [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'type' => $user->type,
                    'phone' => $user->phone,
                ],
                'customer' => [
                    'id' => $user->customer->id,
                    'name' => $user->customer->name,
                ],
            ];
        } catch (Throwable $e) {
            throw new UserServiceException(
                message: 'Failed to load user context.',
                previous: $e
            );
        }
    }

    public function contactDetails(User $user): array
    {
        // No DB query, no need to wrap
        return [
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }

    public function updateContactDetails(User $user, string $email, string $phone): array
    {
        try {
            $user->update([
                'email' => $email,
                'phone' => $phone,
            ]);

            return $this->contactDetails($user);
        } catch (Throwable $e) {
            throw new UserServiceException(
                message: 'Failed to update contact details.',
                previous: $e
            );
        }
    }
}