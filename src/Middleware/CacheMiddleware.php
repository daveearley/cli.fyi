<?php

declare(strict_types=1);

namespace CliFyi\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * This Middleware indiscriminately caches every GET request
 */
class CacheMiddleware
{
    const CACHE_TIMEOUT_IN_SECONDS = 0;

    /** @var CacheInterface */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getMethod() !== 'GET') {
            return $next($request, $response);
        }

        $requestPath = $request->getRequestTarget();
        $cacheKey = sha1($requestPath);

        if ($cachedResponse = $this->cache->get($cacheKey)) {
            return $this->getCachedResponse($response, $cachedResponse);
        }

        $response = $next($request, $response);

        $this->setCachedResponse($response, $cachedResponse, $cacheKey);

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param $cachedResponse
     *
     * @return ResponseInterface
     */
    private function getCachedResponse(ResponseInterface $response, $cachedResponse): ResponseInterface
    {
        $cachedValue = unserialize($cachedResponse);

        if (isset($cachedValue['headers'])) {
            foreach ($cachedValue['headers'] as $headerKey => $headerValue) {
                $response = $response->withHeader($headerKey, $headerValue);
            }
        }

        $response->getBody()->write($cachedValue['body']);

        return $response->withHeader('X-isCached', '1');
    }

    /**
     * @param ResponseInterface $response
     * @param $cachedResponse
     * @param $cacheKey
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function setCachedResponse(ResponseInterface $response, $cachedResponse, $cacheKey): void
    {
        $response->getBody()->rewind();

        $cachedResponse['headers'] = $response->getHeaders();
        $cachedResponse['body'] = $response->getBody()->getContents();

        $this->cache->set($cacheKey, serialize($cachedResponse), self::CACHE_TIMEOUT_IN_SECONDS);
    }
}
