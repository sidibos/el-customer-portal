<?php

namespace App\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function context(User $user): array;

    public function contactDetails(User $user): array;

    public function updateContactDetails(User $user, string $email, string $phone): array;
}