<?php

namespace CliFyi\Middleware;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;

class GoogleAnalyticsMiddleware
{
    const GOOGLE_URI = 'www.google-analytics.com/collect';
    const HTTP_SUCCESSFUL_RESPONSE = 200;

    /** @var string */
    private $googleAnalyticsId;

    /** @var LoggerInterface */
    private $logger;

    /** @var ClientInterface */
    private $httpClient;

    /**
     * @param ClientInterface $httpClient
     * @param LoggerInterface $logger
     * @param string $googleAnalyticsId
     */
    public function __construct(ClientInterface $httpClient, LoggerInterface $logger, string $googleAnalyticsId)
    {
        $this->googleAnalyticsId = $googleAnalyticsId;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    /**
     * @param RequestInterface|Request $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if ($this->shouldLogPageView($request, $response)) {
            try {
                $this->httpClient->request('POST', self::GOOGLE_URI, [
                    'body' => $this->buildRequestBody($request)
                ]);
            } catch (GuzzleException $e) {
                $this->logger->error('Failed to send data to Google Analytics', [$e]);
            }
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function buildRequestBody(Request $request): string
    {
        $ip = $request->getAttribute('ip_address') ?: $request->getServerParam('REMOTE_ADDR');
        $userAgent = $request->getServerParam('HTTP_USER_AGENT') ?: null;
        $referer = $request->getHeader('HTTP_REFERER') ? array_shift($request->getHeader('HTTP_REFERER')) : null;

        $fullUri = $request->getUri()->getScheme()
            . '://' . $request->getUri()->getHost()
            . $request->getRequestTarget();

        return http_build_query(array_filter([
            'v' => '1',
            'tid' => $this->googleAnalyticsId,
            'uid' => md5($ip . $userAgent),
            'dl' => $fullUri,
            'uip' => $ip,
            'ua' => $userAgent,
            't' => 'pageview',
            'dr' => $referer,
            'ds'=> 'web'
        ]));
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function shouldLogPageView(RequestInterface $request, ResponseInterface$response): bool
    {
        return ($response->getStatusCode() === self::HTTP_SUCCESSFUL_RESPONSE) && $request->getRequestTarget() !== '/';
    }
}
