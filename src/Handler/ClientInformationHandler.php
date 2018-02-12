<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\Client\ClientParserInterface;
use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class ClientInformationHandler extends AbstractHandler
{
    private const KEYWORDS = [
        'me',
        'self',
        'ip'
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
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return in_array($searchQuery->toLowerCaseString(), self::KEYWORDS, true);
    }

    /**
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchTerm): array
    {
        $data = [
            'iPAddress' => $this->clientParser->getIpAddress(),
            'userAgent' => $this->clientParser->getUserAgent(),
            'browser' => $this->clientParser->getBrowserName(),
            'operatingSystem' => $this->clientParser->getOperatingSystemName()
        ];

        if ($ipInfo = $this->getIpInfo()) {
            $data['iPAddressInfo'] = $ipInfo;
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
            'organisation' => $this->ipInfoService->getOrganisation(),
            'country' => $this->ipInfoService->getCountry(),
            'countryCode' => $this->ipInfoService->getCountryCode(),
            'city' => $this->ipInfoService->getCity(),
            'continent' => $this->ipInfoService->getContinent(),
            'latitude' => $this->ipInfoService->getLatitude(),
            'longitude' => $this->ipInfoService->getLongitude()
        ]);
    }
}
