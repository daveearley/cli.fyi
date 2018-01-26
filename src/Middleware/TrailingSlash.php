<?php

declare(strict_types=1);

namespace CliFyi\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TrailingSlash
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/' && substr($path, -1) === '/') {
            $uri = $uri->withPath(substr($path, 0, -1));

            return $request->getMethod() === 'GET'
                ? $response->withRedirect((string)$uri, 301)
                : $next($request->withUri($uri), $response);
        }

        return $next($request, $response);
    }
}
