<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use Psr\SimpleCache\CacheInterface;

class IpAddressHandler extends AbstractHandler
{
    /** @var IpAddressInfoProviderInterface */
    private $ipInfoService;

    /**
     * @param IpAddressInfoProviderInterface $ipService
     * @param CacheInterface $cache
     */
    public function __construct(IpAddressInfoProviderInterface $ipService, CacheInterface $cache)
    {
        parent::__construct($cache);

        $this->ipInfoService = $ipService;
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isHandlerEligible(string $ipAddress): bool
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'IP Address';
    }

    /**
     * @param string $searchQuery
     *
     * @return array|null
     */
    public function processSearchTerm(string $searchQuery): array
    {
        $this->ipInfoService->setIpAddress($searchQuery);

        return array_filter([
            'Organisation' => $this->ipInfoService->getOrganisation(),
            'Country' => $this->ipInfoService->getCountry(),
            'City' => $this->ipInfoService->getCity(),
            'Continent' => $this->ipInfoService->getContinent(),
            'Latitude' => $this->ipInfoService->getLatitude(),
            'Longitude' => $this->ipInfoService->getLongitude()
        ]);
    }
}
