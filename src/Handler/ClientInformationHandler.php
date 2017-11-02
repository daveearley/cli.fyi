<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use Psr\SimpleCache\CacheInterface;
use WhichBrowser\Parser;

class ClientInformationHandler extends AbstractHandler
{
    private const KEYWORDS = [
        'me',
        'self'
    ];

    /** @var IpAddressInfoProviderInterface */
    private $ipInfoService;

    /** @var Parser */
    private $userAgentParser;

    /**
     * @param Parser $userAgentParser
     * @param IpAddressInfoProviderInterface $ipAddressInfoProvider
     * @param CacheInterface $cache
     */
    public function __construct(
        Parser $userAgentParser,
        IpAddressInfoProviderInterface $ipAddressInfoProvider,
        CacheInterface $cache
    ) {
        parent::__construct($cache);

        $this->userAgentParser = $userAgentParser;
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
        $this->userAgentParser->analyse($_SERVER['HTTP_USER_AGENT']);

        $data = [
            'User Agent' => $_SERVER['HTTP_USER_AGENT'],
            'IP Address' => $_SERVER['REMOTE_ADDR'],
            'Browser' => $this->userAgentParser->browser->toString(),
            'Operating System' => $this->userAgentParser->os->toString()
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
