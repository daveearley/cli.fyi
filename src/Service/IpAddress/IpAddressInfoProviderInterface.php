<?php

declare(strict_types=1);

namespace CliFyi\Service\IpAddress;

interface IpAddressInfoProviderInterface
{
    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress): void;

    /**
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * @return null|string
     */
    public function getCountry(): ?string;

    /**
     * @return null|string
     */
    public function getCountryCode(): ?string;

    /**
     * @return null|string
     */
    public function getContinent(): ?string;

    /**
     * @return null|string
     */
    public function getOrganisation(): ?string;

    /**
     * @return null|string
     */
    public function getLatitude(): ?string;

    /**
     * @return null|string
     */
    public function getLongitude(): ?string;
}
