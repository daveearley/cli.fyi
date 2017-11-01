<?php

namespace CliFyi\Exception;

interface ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int;
}