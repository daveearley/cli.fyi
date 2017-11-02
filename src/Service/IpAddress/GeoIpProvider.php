<?php

declare(strict_types=1);

namespace CliFyi\Service\IpAddress;

use Exception;
use GeoIp2\Database\Reader;

class GeoIpProvider implements IpAddressInfoProviderInterface
{
    private const ASN_DATABASE = __DIR__ . '/../../../data/geoip/GeoLite2-ASN.mmdb';
    private const CITY_DATABASE = __DIR__ . '/../../../data/geoip/GeoLite2-City.mmdb';

    /** @var $ipAddress */
    private $ipAddress;

    /** @var Reader */
    private $cityReader;

    /** @var Reader */
    private $asnReader;

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return null|string
     */
    public function getOrganisation(): ?string
    {
        try {
            $record = $this->getAsnReader()->asn($this->ipAddress);
        } catch (Exception $e) {
            return null;
        }

        return $record->autonomousSystemOrganization;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->getCityRecord('city');
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->getCityRecord('country');
    }

    /**
     * @return null|string
     */
    public function getLatitude(): ?string
    {
        return $this->getCityRecord('latitude');
    }

    /**
     * @return null|string
     */
    public function getLongitude(): ?string
    {
        return $this->getCityRecord('longitude');
    }

    /**
     * @return null|string
     */
    public function getContinent(): ?string
    {
        return $this->getCityRecord('continent');
    }

    /**
     * @return Reader
     */
    private function getCityReader(): Reader
    {
        if ($this->cityReader instanceof Reader) {
            return $this->cityReader;
        }

        $this->cityReader = new Reader(self::CITY_DATABASE);

        return $this->cityReader;
    }

    /**
     * @return Reader
     */
    private function getAsnReader(): Reader
    {
        if ($this->asnReader instanceof Reader) {
            return $this->asnReader;
        }

        $this->asnReader = new Reader(self::ASN_DATABASE);

        return $this->asnReader;
    }

    /**
     * @param string $recordType
     *
     * @return null|string
     */
    private function getCityRecord(string $recordType): ?string
    {
        try {
            $record = $this->getCityReader()->city($this->ipAddress);
        } catch (Exception $e) {
            return null;
        }

        switch ($recordType) {
            case 'latitude':
                return (string)$record->location->latitude;
            case 'longitude':
                return (string)$record->location->longitude;
            case 'country':
                return (string)$record->country->name;
            case 'city':
                return (string)$record->city->name;
            case 'continent':
                return (string)$record->continent->name;
            default:
                return null;
        }
    }
}
