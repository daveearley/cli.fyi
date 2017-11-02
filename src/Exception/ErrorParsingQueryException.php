<?php

namespace CliFyi\Exception;

use Exception;

class ErrorParsingQueryException extends Exception implements ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 500;
    }
}
