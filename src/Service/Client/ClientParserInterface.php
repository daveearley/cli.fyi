<?php

declare(strict_types=1);

namespace CliFyi\Service\Client;

interface ClientParserInterface
{
    /**
     * @return string
     */
    public function getBrowserName(): ?string;

    /**
     * @return string
     */
    public function getOperatingSystemName(): ?string;

    /**
     * @return string
     */
    public function getUserAgent(): ?string ;

    /**
     * @return string
     */
    public function getIpAddress(): ?string ;
}