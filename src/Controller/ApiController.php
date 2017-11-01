<?php

declare(strict_types=1);

namespace CliFyi\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use CliFyi\Builder\ResponseBuilder;
use CliFyi\Factory\HandlerFactory;
use CliFyi\Handler\AbstractHandler;

class ApiController
{
    const UNABLE_TO_PARSE_MESSAGE = '😢 Sorry, we don\'t know how to parse \'%s\' at this time';

    /** @var string */
    private $searchQuery;

    /** @var HandlerFactory */
    private $handlerFactory;

    /** @var AbstractHandler */
    private $handler;

    /** @var ResponseBuilder */
    private $responseBuilder;

    /**
     * @param ResponseBuilder $responseBuilder
     * @param HandlerFactory $handlerFactory
     */
    public function __construct(ResponseBuilder $responseBuilder, HandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
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

        return $this->responseBuilder
            ->withResponse($response)
            ->withStatus(ResponseBuilder::HTTP_STATUS_NOT_FOUND)
            ->withJsonArray([sprintf(self::UNABLE_TO_PARSE_MESSAGE, $this->searchQuery)])
            ->getBuiltResponse();
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
     * @return array
     */
    private function buildOutputArray(): array
    {
        $data = $this->handler->setSearchTerm($this->searchQuery)->getData();

        if (empty($data)) {
            return [];
        }

        return [
            'type' => $this->handler->getHandlerName(),
            'data' => $data
        ];
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
