<?php

namespace CliFyi\Handler;

use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class HashHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'String Hash Values';

    public const TRIGGER_KEYWORD = 'hash/';

    /** @var HasherInterface */
    private $hasher;

    /**
     * @param CacheInterface $cache
     * @param HasherInterface $hasher
     */
    public function __construct(CacheInterface $cache, HasherInterface $hasher)
    {
        parent::__construct($cache);

        $this->hasher = $hasher;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return self::HANDLER_NAME;
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return (stripos($searchQuery->toLowerCaseString(), self::TRIGGER_KEYWORD) === 0);
    }

    /**
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchTerm): array
    {
        return $this->hasher->getHashValuesFromString($this->formatSearchTerm($searchTerm->toLowerCaseString()));
    }

    /**
     * @param string $searchTerm
     *
     * @return string
     */
    private function formatSearchTerm(string $searchTerm): string
    {
        return str_replace(self::TRIGGER_KEYWORD, '', $searchTerm);
    }
}
