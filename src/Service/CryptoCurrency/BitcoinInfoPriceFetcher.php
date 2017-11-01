<?php

declare(strict_types=1);

namespace CliFyi\Service\CryptoCurrency;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class BitcoinInfoPriceFetcher implements PriceFetchInterface
{
    const API_END_POINT = 'https://blockchain.info/ticker';
    const HTTP_STATUS_OK = 200;

    /** @var ClientInterface */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function getPrices(): array
    {
        $result = [];
        if ($prices = $this->fetchPrices()) {
            foreach ($prices as $currency => $data) {
                $result[$currency . ' (' . $data['symbol'] . ')'] = $data['last'];
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
    private function fetchPrices(): array
    {
        try {
            $response = $this->client->request('GET', self::API_END_POINT);
        } catch (GuzzleException $e) {
            return [];
        }

        if ($response->getStatusCode() === self::HTTP_STATUS_OK) {
            return $this->formatResponse($response->getBody());
        }

        return [];
    }
}
