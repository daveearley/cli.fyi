<?php

declare(strict_types=1);

namespace CliFyi\Builder;

use Psr\Http\Message\ResponseInterface;

class ResponseBuilder
{
    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_NOT_FOUND = 404;

    /** @var ResponseInterface */
    private $response;

    /** @var int */
    private $statusCode = self::HTTP_STATUS_OK;

    /** @var array */
    private $jsonArray;

    /** @var array */
    private $headers;

    /**
     * @param ResponseInterface $response
     *
     * @return ResponseBuilder
     */
    public function withResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param string $headerKey
     * @param string $headerValue
     *
     * @return ResponseBuilder
     */
    public function withHeader(string $headerKey, string $headerValue): self
    {
        $this->headers[$headerKey] = $headerValue;

        return $this;
    }

    /**
     * @param array $jsonArray
     *
     * @return ResponseBuilder
     */
    public function withJsonArray(array $jsonArray): self
    {
        $this->jsonArray = $jsonArray;

        return $this;
    }

    /**
     * @param int $statusCode
     *
     * @return ResponseBuilder
     */
    public function withStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getBuiltResponse(): ResponseInterface
    {
        $this->buildHeaders();

        return $this->response->withJson(
            $this->jsonArray,
            $this->statusCode,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function buildHeaders(): void
    {
        if ($this->headers) {
            foreach ($this->headers as $headerKey => $headerValue) {
                $this->response->withHeader($headerKey, $headerValue);
            }
        }
    }
}
