<?php

namespace CliFyi\ErrorHandler;

use CliFyi\Exception\ApiExceptionInterface;
use CliFyi\Exception\ErrorParsingQueryException;
use CliFyi\Exception\NoAvailableHandlerException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;
use Throwable;

class ErrorHandler
{
    const GENERIC_ERROR_MESSAGE = '<h2>Something has gone wrong on our server! We will look into it ASAP</h2>';
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @var LoggerInterface */
    private $logger;

    /** @var bool */
    private $debug;

    /**
     * @param LoggerInterface $logger
     * @param bool $debug
     */
    public function __construct(LoggerInterface $logger, bool $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
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
                ->withJson(
                    ['error' => $this->debug ? $this->getFormattedExceptionData($exception) : $exception->getMessage()]
                );
        }

        $response = $response
            ->withStatus(self::HTTP_INTERNAL_SERVER_ERROR)
            ->withHeader('Content-Type', 'text/html');

        $debugData = $this->debug
            ? '<pre>' . json_encode($this->getFormattedExceptionData($exception), JSON_PRETTY_PRINT) . '</pre>'
            : '';

        $response->getBody()->write(self::GENERIC_ERROR_MESSAGE . $debugData);

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
            $this->logger->debug($exception->getMessage(), ['message' => $exception->getMessage()]);
        } else {
            $this->logger->critical(
                $exception->getMessage(),
                $this->getFormattedExceptionData(
                    ($exception instanceof ErrorParsingQueryException)
                        ? $exception->getPrevious()
                        : $exception
                )
            );
        }
    }
}
