<?php

declare(strict_types=1);

namespace CliFyi\Service\CryptoCurrency;

interface PriceFetchInterface
{
    /**
     * @param string $cryptoCurrency
     *
     * @return array [['USD' => '7666.4']]
     */
    public function getPrices(string $cryptoCurrency): array;
}
