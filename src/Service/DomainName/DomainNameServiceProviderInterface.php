<?php

declare(strict_types=1);

namespace CliFyi\Service\DomainName;

interface DomainNameServiceProviderInterface
{
    /**
     * @param string $hostName
     *
     * @return array
     */
    public function getDnsData(string $hostName): array;

    /**
     * @param string $hostName
     *
     * @return array
     */
    public function getWhoisData(string $hostName): array;
}
