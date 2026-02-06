<?php

namespace App\Exceptions;

use Throwable;

class UserServiceException extends ServiceException
{
    public function __construct(
        string $message = 'User service error',
        int $status = 500,
        string $errorCode = 'user_service_error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $errorCode, $previous);
    }

    public function service(): string
    {
        return 'UserService';
    }
}