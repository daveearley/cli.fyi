<?php

declare(strict_types=1);

namespace CliFyi\Handler;

class CountryHandler extends AbstractHandler
{
    const COUNTRY_DATA_LOCATION = __DIR__ . '/../../data/countries_data.php';

    /** @var mixed */
    private static $countryData;

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Country Query';
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        if (self::isCountry($searchQuery)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    private static function isCountry(string $searchQuery): bool
    {
        return isset(self::getCountryData()[trim(strtolower($searchQuery))]);
    }

    /**
     * @return array
     */
    private static function getCountryData(): array
    {
        if (self::$countryData) {
            return self::$countryData;
        }

        self::$countryData = include self::COUNTRY_DATA_LOCATION;

        return self::$countryData;
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(string $searchTerm): array
    {
        return self::getCountryData()[trim(strtolower($searchTerm))];
    }
}
