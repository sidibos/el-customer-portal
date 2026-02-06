<?php

namespace App\Exceptions;

use Throwable;

class DashboardServiceException extends ServiceException
{
    public function __construct(
        string $message = 'Dashboard service error',
        int $status = 500,
        string $errorCode = 'dashboard_service_error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $errorCode, $previous);
    }

    public function service(): string
    {
        return 'DashboardService';
    }
}