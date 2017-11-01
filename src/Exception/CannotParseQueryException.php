<?php

namespace CliFyi\Exception;

use Exception;

class CannotParseQueryException extends Exception implements ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 404;
    }
}
