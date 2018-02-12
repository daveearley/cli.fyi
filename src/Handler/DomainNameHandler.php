<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\DomainName\DomainNameServiceProviderInterface;
use CliFyi\Transformer\TransformerInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class DomainNameHandler extends AbstractHandler
{
    private const DOMAIN_REGEX = '/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/';

    /** @var DomainNameServiceProviderInterface */
    private $domainNameServiceProvider;

    /**
     * @param DomainNameServiceProviderInterface $domainNameServiceProvider
     * @param CacheInterface $cache
     * @param TransformerInterface $transformer
     */
    public function __construct(
        DomainNameServiceProviderInterface $domainNameServiceProvider,
        CacheInterface $cache,
        TransformerInterface $transformer
    ) {
        parent::__construct($cache, $transformer);

        $this->domainNameServiceProvider = $domainNameServiceProvider;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Domain Name Information';
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return preg_match(self::DOMAIN_REGEX, $searchQuery->toLowerCaseString()) === 1;
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        return [
            'whois' => $this->domainNameServiceProvider->getWhoisData($searchQuery->toLowerCaseString()),
            'dns' => $this->domainNameServiceProvider->getDnsData($searchQuery->toLowerCaseString())
        ];
    }
}
