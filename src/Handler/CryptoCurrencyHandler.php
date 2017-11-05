<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use Psr\SimpleCache\CacheInterface;
use CliFyi\Service\CryptoCurrency\PriceFetchInterface;

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
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return in_array(strtoupper(trim($searchQuery)), array_keys(self::getCryptoCurrencyData()), true);
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
    {
        if ($prices = $this->priceFetcher->getPrices($searchQuery)) {
            $this->handlerName = self::getCryptoCurrencyData()[strtoupper(trim($searchQuery))] . ' Prices';

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
