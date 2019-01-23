<?php

namespace CliFyi\Exception;

use RuntimeException;

class EnvValueException extends RuntimeException implements ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 500;
    }
}
