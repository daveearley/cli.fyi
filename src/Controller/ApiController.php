<?php

declare(strict_types=1);

namespace CliFyi\Controller;

use CliFyi\Exception\NoAvailableHandlerException;
use CliFyi\Exception\ErrorParsingQueryException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use CliFyi\Builder\ResponseBuilder;
use CliFyi\Factory\HandlerFactory;
use CliFyi\Handler\AbstractHandler;
use Psr\Log\LoggerInterface;
use Throwable;

class ApiController
{
    const UNABLE_TO_PARSE_MESSAGE = '😢 Sorry, we don\'t know how to parse \'%s\' at this time';
    const ERROR_WHILE_PARSING_MESSAGE = 'Sorry, we encountered an error while parsing \'%s\'';

    /** @var string */
    private $searchQuery;

    /** @var HandlerFactory */
    private $handlerFactory;

    /** @var AbstractHandler */
    private $handler;

    /** @var ResponseBuilder */
    private $responseBuilder;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param ResponseBuilder $responseBuilder
     * @param HandlerFactory $handlerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResponseBuilder $responseBuilder,
        HandlerFactory $handlerFactory,
        LoggerInterface $logger
    ) {
        $this->handlerFactory = $handlerFactory;
        $this->responseBuilder = $responseBuilder;
        $this->logger = $logger;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @throws NoAvailableHandlerException
     * @throws ErrorParsingQueryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $this->searchQuery = $this->getSearchTerm($request);

        if ($this->handler = $this->isHandlerAvailable()) {
            if ($output = $this->buildOutputArray()) {
                return $this->responseBuilder
                    ->withResponse($response)
                    ->withJsonArray($this->buildOutputArray())
                    ->getBuiltResponse();
            }
        }

        throw new NoAvailableHandlerException(sprintf(self::UNABLE_TO_PARSE_MESSAGE, $this->searchQuery));
    }

    /**
     * @return null|AbstractHandler
     */
    private function isHandlerAvailable(): ?AbstractHandler
    {
        foreach ($this->handlerFactory->getAvailableHandlers() as $availableHandler) {
            if ($availableHandler::isHandlerEligible($this->searchQuery)) {
                return $this->handlerFactory->create($availableHandler);
            }
        }

        return null;
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws ErrorParsingQueryException
     *
     * @return array
     */
    private function buildOutputArray(): array
    {
        $data = $this->getParsedData();

        if (empty($data)) {
            return [];
        }

        return [
            'type' => $this->handler->getHandlerName(),
            'data' => $data
        ];
    }

    /**
     * @throws ErrorParsingQueryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return array
     */
    private function getParsedData(): array
    {
        try {
            return $this->handler->setSearchTerm($this->searchQuery)->getData();
        } catch (Throwable $e) {
            $this->logger->critical('Failed to render response', [$e]);

            throw new ErrorParsingQueryException(sprintf(self::ERROR_WHILE_PARSING_MESSAGE, $this->searchQuery));
        }
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    private function getSearchTerm(RequestInterface $request): string
    {
        return ltrim($request->getRequestTarget(), '/');
    }
}
