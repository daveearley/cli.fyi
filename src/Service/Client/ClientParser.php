<?php

namespace CliFyi\Service\Client;

use Psr\Http\Message\RequestInterface;
use WhichBrowser\Parser;

class ClientParser implements ClientParserInterface
{
    /** @var Parser */
    private $clientParser;

    /** @var RequestInterface */
    private $request;

    /**
     * @param RequestInterface $request
     * @param Parser $clientParser
     */
    public function __construct(RequestInterface $request, Parser $clientParser)
    {
        $this->request = $request;
        $this->clientParser = $clientParser;
        $this->clientParser->analyse($this->getUserAgent());
    }

    /**
     * @return string
     */
    public function getBrowserName(): ?string
    {
        return $this->clientParser->browser->toString();
    }

    /**
     * @return string
     */
    public function getOperatingSystemName(): ?string
    {
        return $this->clientParser->os->toString();
    }

    /**
     * @return string
     */
    public function getUserAgent(): ?string
    {
        return $this->request->getHeaderLine('HTTP_USER_AGENT');
    }

    /**
     * @return string
     */
    public function getIpAddress(): ?string
    {
        return $this->request->getAttribute('ip_address') ?: $_SERVER['REMOTE_ADDR'];
    }
}
