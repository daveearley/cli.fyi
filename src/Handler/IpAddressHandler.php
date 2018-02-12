<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class IpAddressHandler extends AbstractHandler
{
    const IP_VALIDATION_FLAGS = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;
    const RESERVED_OR_PRIVATE_IP_MESSAGE = '%s falls within a %s IP range and therefore no data is available.';

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

        $this->disableCache();
    }

    /**
     * @param SearchTerm $ipAddress
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $ipAddress): bool
    {
        return filter_var($ipAddress->toString(), FILTER_VALIDATE_IP, self::IP_VALIDATION_FLAGS) !== false;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'IP Address';
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array|null
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        $this->ipInfoService->setIpAddress($searchQuery->toString());

        return array_filter([
            'organisation' => $this->ipInfoService->getOrganisation(),
            'country' => $this->ipInfoService->getCountry(),
            'countryCode' => $this->ipInfoService->getCountryCode(),
            'city' => $this->ipInfoService->getCity(),
            'continent' => $this->ipInfoService->getContinent(),
            'latitude' => $this->ipInfoService->getLatitude(),
            'longitude' => $this->ipInfoService->getLongitude(),
            'isIpInPrivateRange' => $this->isInPrivateIpRange($searchQuery->toString()),
            'isIpInReservedRange' => $this->isInReservedIpRange($searchQuery->toString())
        ]);
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    private function isInPrivateIpRange(string $ipAddress): bool
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) !== $ipAddress;
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    private function isInReservedIpRange(string $ipAddress): bool
    {
        return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) !== $ipAddress;
    }
}
