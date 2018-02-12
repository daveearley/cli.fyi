<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\CryptoCurrency\PriceFetchInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class CryptoCurrencyHandler extends AbstractHandler
{
    const COUNTRY_DATA_LOCATION = __DIR__ . '/../../data/crypto_currency_data.php';

    /** @var PriceFetchInterface */
    private $priceFetcher;

    /** @var array */
    private static $cryptoCurrencyData;

    /**
     * @param CacheInterface $cache
     * @param PriceFetchInterface $priceFetch
     */
    public function __construct(CacheInterface $cache, PriceFetchInterface $priceFetch)
    {
        parent::__construct($cache);

        $this->priceFetcher = $priceFetch;
    }

    /** @var */
    private $handlerName = 'Crypto Currency';

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return $this->handlerName;
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return in_array($searchQuery->toUpperCaseString(), array_keys(self::getCryptoCurrencyData()), true);
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        if ($prices = $this->priceFetcher->getPrices($searchQuery->toUpperCaseString())) {
            $this->handlerName = self::getCryptoCurrencyData()[$searchQuery->toUpperCaseString()] . ' Prices';

            return $prices;
        }

        return null;
    }

    /**
     * @return array
     */
    private static function getCryptoCurrencyData(): array
    {
        if (self::$cryptoCurrencyData) {
            return self::$cryptoCurrencyData;
        }

        self::$cryptoCurrencyData = include self::COUNTRY_DATA_LOCATION;

        return self::$cryptoCurrencyData;
    }
}
