<?php

namespace App\Exceptions;

use Throwable;

class ConsumptionServiceException extends ServiceException
{
    public function __construct(
        string $message = 'Consumption service error',
        int $status = 500,
        string $errorCode = 'consumption_service_error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $errorCode, $previous);
    }

    public function service(): string
    {
        return 'ConsumptionService';
    }
}