<?php

namespace CliFyi\Handler;

use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class HashHandler extends AbstractHandler
{
    public const TRIGGER_KEYWORD = 'hash/';

    /** @var HasherInterface */
    private $hasher;

    private $handlerName = 'String Hash Values For: ';

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
        return $this->handlerName;
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
        $valueToHash = $this->formatSearchTerm($searchTerm->toString());
        $this->setHandlerName($valueToHash);

        return $this->hasher->getHashValuesFromString($valueToHash);
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

    /**
     * @param string $searchTerm
     */
    private function setHandlerName(string $searchTerm)
    {
        $this->handlerName .= ' ('. $searchTerm . ')';
    }
}
