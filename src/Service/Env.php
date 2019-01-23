<?php

namespace CliFyi\Service;

use CliFyi\Exception\EnvValueException;

class Env
{
    /**
     * @param string $key
     *
     * @return string
     */
    public function getStringValue(string $key): string
    {
        return (string) $this->getValue($key);
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function getIntValue(string $key): int
    {
        return (int) $this->getValue($key);
    }

    /**
     * @param string $key
     *
     * @return array|false|string
     */
    private function getValue(string $key) {
        if ($value = getenv($key)) {
            return $value;
        }

        throw new EnvValueException(sprintf('%s value not set in .env', $key), 500);
    }
}
