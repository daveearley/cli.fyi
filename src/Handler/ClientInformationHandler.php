<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\Client\ClientParserInterface;
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

    /** @var ClientParserInterface */
    private $clientParser;

    /**
     * @param ClientParserInterface $clientParser
     * @param IpAddressInfoProviderInterface $ipAddressInfoProvider
     * @param CacheInterface $cache
     */
    public function __construct(
        ClientParserInterface $clientParser,
        IpAddressInfoProviderInterface $ipAddressInfoProvider,
        CacheInterface $cache
    ) {
        parent::__construct($cache);

        $this->clientParser = $clientParser;
        $this->ipInfoService = $ipAddressInfoProvider;

        $this->disableCache();
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Client Information Query';
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
            'User Agent' => $this->clientParser->getUserAgent(),
            'IP Address' => $this->clientParser->getIpAddress(),
            'Browser' => $this->clientParser->getBrowserName(),
            'Operating System' => $this->clientParser->getOperatingSystemName()
        ];

        if ($ipInfo = $this->getIpInfo()) {
            $data['IP Address Info'] = $ipInfo;
        }

        return array_filter($data);
    }

    /**
     * @return array|null
     */
    private function getIpInfo(): ?array
    {
        $this->ipInfoService->setIpAddress($this->clientParser->getIpAddress());

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
