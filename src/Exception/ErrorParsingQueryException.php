<?php

namespace CliFyi\Exception;

use Exception;
use Throwable;

class ErrorParsingQueryException extends Exception implements ApiExceptionInterface
{
    /**
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(string $message, Throwable $previous)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 500;
    }
}
