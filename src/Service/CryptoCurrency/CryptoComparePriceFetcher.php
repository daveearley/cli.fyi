<?php

declare(strict_types=1);

namespace CliFyi\Service\CryptoCurrency;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class CryptoComparePriceFetcher implements PriceFetchInterface
{
    const API_END_POINT = 'https://min-api.cryptocompare.com/data/price?fsym=%s&tsyms=%s';
    const HTTP_STATUS_OK = 200;

    /** @var ClientInterface */
    private $client;

    /** @var array */
    private static $availableFiatCurrencies = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'AUD' => '$',
        'CAD' => '$',
        'BRL' => 'R$',
        'CHF' => 'CHF',
        'CLP' => '$',
        'CNY' => '¥',
        'DKK' => 'kr',
        'HKD' => '$',
        'INR' => '₹',
        'ISK' => 'kr',
        'JPY' => '¥',
        'KRW' => '₩',
        'NZD' => '$',
        'PLN' => 'zł',
        'RUB' => 'RUB',
        'SEK' => 'kr',
        'SGD' => '$',
        'THB' => '฿',
        'TWD' => 'NT$'
    ];

    /** @var string */
    private $cryptoCurrency;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $cryptoCurrency
     *
     * @return array
     */
    public function getPrices(string $cryptoCurrency): array
    {
        $this->cryptoCurrency = $cryptoCurrency;

        $result = [];
        if ($prices = $this->fetchPrices()) {
            foreach ($prices as $currency => $price) {
                $result[$currency . ' (' . self::getAvailableFiatCurrencies()[$currency] . ')'] = $price;
            }
        }

        return $result;
    }

    /**
     * @param string $jsonBody
     *
     * @return array
     */
    private function formatResponse(string $jsonBody): array
    {
        try {
            $responseArray = \GuzzleHttp\json_decode($jsonBody, true);
        } catch (InvalidArgumentException $exception) {
            return [];
        }

        return $responseArray;
    }

    /**
     * @return array
     */
    public static function getAvailableFiatCurrencies(): array
    {
        return self::$availableFiatCurrencies;
    }

    /**
     * @return array
     */
    private function fetchPrices(): array
    {
        try {
            $response = $this->client->request('GET', $this->buildEndpointUrl());
        } catch (GuzzleException $e) {
            return [];
        }

        if ($response->getStatusCode() === self::HTTP_STATUS_OK) {
            return $this->formatResponse($response->getBody()->getContents());
        }

        return [];
    }

    /**
     * @return string
     */
    private function buildEndpointUrl(): string
    {
        return sprintf(
            self::API_END_POINT,
            strtoupper($this->cryptoCurrency),
            implode(',', array_keys(self::getAvailableFiatCurrencies()))
        );
    }
}
