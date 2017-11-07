<?php

namespace CliFyi\Middleware;

use KeenIO\Client\KeenIOClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class keenTrackingMiddleware
{
    /** @var string */
    private $projectId;

    /** @var string */
    private $writeId;

    public function __construct(string $keenProjectId, string $keenWriteId)
    {
        $this->projectId = $keenWriteId;
        $this->writeId = $keenWriteId;
    }

    const HTTP_SUCCESSFUL_RESPONSE = 200;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        if ($response->getStatusCode() === self::HTTP_SUCCESSFUL_RESPONSE) {
            $client = KeenIOClient::factory([
                'projectId' => getenv('KEEN_PROJECT_ID'),
                'writeKey' => getenv('KEEN_WRITE_ID')
            ]);

            $client->addEvent(
                'query',
                [
                    'term' => ltrim($request->getRequestTarget(), '/'),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?: 'Unknown',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?: 'Unknown'
                ]
            );
        }

        return $response;
    }
}
