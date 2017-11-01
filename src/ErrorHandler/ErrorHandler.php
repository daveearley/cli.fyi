<?php

namespace CliFyi\ErrorHandler;

use CliFyi\Exception\ApiExceptionInterface;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $exception
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        if ($exception instanceof ApiExceptionInterface) {
            return $response
                ->withStatus($exception->getStatusCode())
                ->withJson(['error' => $exception->getMessage()]);
        }


        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->withBody('Something went wrong!');
    }
}
