<?php

namespace CliFyi\Handler;

use CliFyi\Service\Hash\HasherInterface;
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
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return (stripos($searchQuery, self::TRIGGER_KEYWORD) === 0);
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(string $searchTerm): array
    {
        return $this->hasher->getHashValuesFromString($this->formatSearchTerm($searchTerm));
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
