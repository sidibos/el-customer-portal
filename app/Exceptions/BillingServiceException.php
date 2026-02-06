<?php

namespace App\Exceptions;

use Throwable;

class BillingServiceException extends ServiceException
{
    public function __construct(
        string $message = 'Billing service error',
        int $status = 500,
        string $errorCode = 'billing_service_error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $errorCode, $previous);
    }

    public function service(): string
    {
        return 'BillingPreferenceService';
    }
}

