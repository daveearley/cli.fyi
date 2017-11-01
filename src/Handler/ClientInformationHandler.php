<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use Psr\SimpleCache\CacheInterface;

class ClientInformationHandler extends AbstractHandler
{
    private const KEYWORDS = [
        'me',
        'self'
    ];

    /** @var IpAddressInfoProviderInterface */
    private $ipInfoService;

    public function __construct(IpAddressInfoProviderInterface $ipAddressInfoProvider, CacheInterface $cache)
    {
        parent::__construct($cache);

        $this->ipInfoService = $ipAddressInfoProvider;

        $this->disableCache();
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Client Query';
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return in_array($searchQuery, self::KEYWORDS, true);
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(string $searchTerm): array
    {
        $data = [
            'User Agent' => $_SERVER['HTTP_USER_AGENT'],
            'IP Address' => $_SERVER['REMOTE_ADDR'],
        ];

        if ($ipInfo = $this->getIpInfo()) {
            $data['IP Address Info'] = $ipInfo;
        }

        return $data;
    }

    /**
     * @return array|null
     */
    private function getIpInfo(): ?array
    {
        $this->ipInfoService->setIpAddress($_SERVER['REMOTE_ADDR']);

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
