<?php

namespace CliFyi\ErrorHandler;

use CliFyi\Exception\ApiExceptionInterface;
use CliFyi\Exception\NoAvailableHandlerException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;
use Throwable;

class ErrorHandler
{
    const GENERIC_ERROR_MESSAGE = 'Something has gone wrong on our server! We will look into it ASAP';
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface|Response $response
     * @param Throwable $exception
     *
     * @return ResponseInterface
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        Throwable $exception
    ): ResponseInterface {

        $this->logException($exception);

        if ($exception instanceof ApiExceptionInterface) {
            return $response
                ->withStatus($exception->getStatusCode())
                ->withJson(['error' => $exception->getMessage()]);


        }

        $response = $response
            ->withStatus(self::HTTP_INTERNAL_SERVER_ERROR)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write(self::GENERIC_ERROR_MESSAGE);

        return $response;
    }

    /**
     * @param Throwable $exception
     *
     * @return array
     */
    private function getFormattedExceptionData(Throwable $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile() . ':' . $exception->getLine(),
            'exceptionType' => get_class($exception),
            'trace' => $exception->getTrace(),
            'previous' => $exception->getPrevious()
        ];
    }

    /**
     * @param Throwable $exception
     */
    private function logException(Throwable $exception): void
    {
        if ($exception instanceof NoAvailableHandlerException) {
            $this->logger->notice($exception->getMessage(), $this->getFormattedExceptionData($exception));
        } else {
            $this->logger->critical($exception->getMessage(), $this->getFormattedExceptionData($exception));
        }
    }
}
