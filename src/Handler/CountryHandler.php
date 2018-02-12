<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Value\SearchTerm;

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
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        if (self::isCountry($searchQuery->toLowerCaseString())) {
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
        return isset(self::getCountryData()[trim($searchQuery)]);
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
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchTerm): array
    {
        return self::getCountryData()[trim($searchTerm->toLowerCaseString())];
    }
}
