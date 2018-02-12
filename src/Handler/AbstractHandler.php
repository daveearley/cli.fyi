<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Exception\NoDataReturnedFromHandlerException;
use CliFyi\Transformer\TransformerInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

abstract class AbstractHandler
{
    private const NO_DATA_ERROR_MESSAGE = 'No data returned from handler.';

    protected const DEFAULT_CACHE_TTL_IN_SECONDS = 60 * 15;

    /** @var TransformerInterface */
    private $transformer;

    /** @var SearchTerm */
    private $searchTerm;

    /** @var CacheInterface */
    private $cache;

    /** @var int */
    private $cacheTtlInSeconds = self::DEFAULT_CACHE_TTL_IN_SECONDS;

    /** @var bool */
    private $cacheEnabled = true;

    /**
     * @param CacheInterface $cache
     * @param TransformerInterface|null $transformer
     */
    public function __construct(CacheInterface $cache, TransformerInterface $transformer = null)
    {
        $this->cache = $cache;
        $this->transformer = $transformer;
    }

    /**
     * @return string
     */
    abstract public function getHandlerName(): string;

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    abstract public static function isHandlerEligible(SearchTerm $searchQuery): bool;

    /**
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    abstract public function processSearchTerm(SearchTerm $searchTerm): array;

    /**
     * @throws InvalidArgumentException
     * @throws NoDataReturnedFromHandlerException
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->cacheEnabled && $cachedValue = $this->cache->get($this->getCacheKey())) {
            return $cachedValue;
        }

        return $this->cacheAndReturn($this->buildResponseArray());
    }

    /**
     * @param SearchTerm $searchTerm
     *
     * @return AbstractHandler
     */
    public function setSearchTerm(SearchTerm $searchTerm): AbstractHandler
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @return SearchTerm
     */
    public function getSearchTerm(): SearchTerm
    {
        return $this->searchTerm;
    }

    /**
     * @return int
     */
    protected function getCacheTtl(): int
    {
        return $this->cacheTtlInSeconds;
    }

    /**
     * @param int $cacheTtl
     *
     * @return int
     */
    protected function setCacheTtl(int $cacheTtl): int
    {
        $this->cacheTtlInSeconds = $cacheTtl;
    }

    /**
     * @return void
     */
    protected function disableCache(): void
    {
        $this->cacheEnabled = false;
    }

    /**
     * @return string
     */
    private function getCacheKey(): string
    {
        return md5($this->searchTerm->toString());
    }

    /**
     * @param array $data
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    private function cacheAndReturn(array $data): array
    {
        if ($this->cacheEnabled) {
            $this->cache->set($this->getCacheKey(), $data, $this->getCacheTtl());
        }

        return $data;
    }

    /**
     * @throws NoDataReturnedFromHandlerException
     *
     * @return array
     */
    private function buildResponseArray(): array
    {
        $handlerData = $this->processSearchTerm($this->getSearchTerm());
        $handlerName = $this->getHandlerName();

        if (empty($handlerData)) {
            throw new NoDataReturnedFromHandlerException(self::NO_DATA_ERROR_MESSAGE);
        }

        if ($this->transformer) {
            $handlerData = $this->transformer->transform($handlerData);
        }

        return [
            'type' => $handlerName,
            'data' => $handlerData
        ];
    }
}
