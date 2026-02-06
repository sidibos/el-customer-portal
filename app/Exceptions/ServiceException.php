<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

abstract class ServiceException extends RuntimeException
{
    public function __construct(
        string $message,
        protected int $status = 500,
        protected string $errorCode = 'service_error',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    abstract public function service(): string;

    public function status(): int
    {
        return $this->status;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }
}