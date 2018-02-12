<?php

declare(strict_types=1);

namespace CliFyi\Controller;

use CliFyi\Builder\ResponseBuilder;
use CliFyi\Exception\ErrorParsingQueryException;
use CliFyi\Exception\NoAvailableHandlerException;
use CliFyi\Factory\HandlerFactory;
use CliFyi\Handler\AbstractHandler;
use CliFyi\Value\SearchTerm;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ApiController
{
    const UNABLE_TO_PARSE_MESSAGE = 'Sorry, we don\'t know how to parse \'%s\' at this time';
    const ERROR_WHILE_PARSING_MESSAGE = 'Sorry, we encountered an error while parsing \'%s\'';

    /** @var SearchTerm */
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
     * @throws ErrorParsingQueryException
     * @throws NoAvailableHandlerException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $this->searchQuery = $this->getSearchTerm($request);

        if ($this->handler = $this->isHandlerAvailable()) {
            if ($output = $this->getParsedData()) {
                return $this->responseBuilder
                    ->withResponse($response)
                    ->withJsonArray($output)
                    ->getBuiltResponse();
            }
        }

        throw new NoAvailableHandlerException(sprintf(self::UNABLE_TO_PARSE_MESSAGE, urldecode($this->searchQuery->toString())));
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
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
     * @throws ErrorParsingQueryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return array
     */
    private function getParsedData(): array
    {
        try {
            return $this->handler->setSearchTerm($this->searchQuery)->getData();
        } catch (Throwable $exception) {
            throw new ErrorParsingQueryException(
                sprintf(self::ERROR_WHILE_PARSING_MESSAGE, urldecode($this->searchQuery->toString())),
                $exception
            );
        }
    }

    /**
     * @param RequestInterface $request
     *
     * @return SearchTerm
     */
    private function getSearchTerm(RequestInterface $request): SearchTerm
    {
        return new SearchTerm(trim(ltrim($request->getRequestTarget(), '/')));
    }
}
