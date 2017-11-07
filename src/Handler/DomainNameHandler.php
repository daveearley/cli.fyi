<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\DomainName\DomainNameServiceProviderInterface;
use CliFyi\Transformer\TransformerInterface;
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
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return preg_match(self::DOMAIN_REGEX, trim($searchQuery)) === 1;
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
    {
        $data['whois'] = $this->domainNameServiceProvider->getWhoisData($searchQuery);
        $data['dns'] =  $this->domainNameServiceProvider->getDnsData($searchQuery);

        return $data;
    }
}
