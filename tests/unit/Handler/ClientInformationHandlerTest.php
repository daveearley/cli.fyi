<?php

namespace Test\Handler;

use CliFyi\Handler\ClientInformationHandler;
use CliFyi\Handler\IpAddressHandler;
use CliFyi\Service\Client\ClientParserInterface;
use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use Mockery;
use Mockery\MockInterface;

class ClientInformationHandlerTest extends BaseHandlerTestCase
{
    /** @var ClientParserInterface|MockInterface */
    private $clientParser;

    /** @var IpAddressInfoProviderInterface|MockInterface */
    private $ipInfoService;

    /** @var IpAddressHandler */
    private $clientInfoHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->clientParser = Mockery::mock(ClientParserInterface::class);
        $this->ipInfoService = Mockery::mock(IpAddressInfoProviderInterface::class);
        $this->clientInfoHandler = new ClientInformationHandler(
            $this->clientParser,
            $this->ipInfoService,
            $this->cache
        );
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Client Information Query', $this->clientInfoHandler->getHandlerName());
    }

    public function testIsHandlerEligibleForValidKeywords()
    {
        foreach (['ME', 'me', 'self'] as $keyword) {
            $this->assertTrue(ClientInformationHandler::isHandlerEligible($keyword));
        }
    }

    public function testIsHandlerEligibleForInvalidKeywords()
    {
        foreach (['not', 'valid', 'keywords'] as $keyword) {
            $this->assertFalse(ClientInformationHandler::isHandlerEligible($keyword));
        }
    }

    public function testParseSearchTerm()
    {
        $this->clientParser->shouldReceive('getUserAgent')->andReturn('curl');
        $this->clientParser->shouldReceive('getIpAddress')->andReturn('123.123.123.123');
        $this->clientParser->shouldReceive('getBrowserName')->andReturn('curl');
        $this->clientParser->shouldReceive('getOperatingSystemName')->andReturn('Linux');

        $this->ipInfoService->shouldReceive('setIpAddress', '123.123.123.123')->andReturn();
        $this->ipInfoService->shouldReceive('getOrganisation')->andReturn('Acme');
        $this->ipInfoService->shouldReceive('getCountry')->andReturn('Ireland');
        $this->ipInfoService->shouldReceive('getCity')->andReturn('Dublin');
        $this->ipInfoService->shouldReceive('getContinent')->andReturn('Europe');
        $this->ipInfoService->shouldReceive('getLatitude')->andReturn('123');
        $this->ipInfoService->shouldReceive('getLongitude')->andReturn('123');

        $expected = [
            'userAgent' => 'curl',
            'iPAddress' => '123.123.123.123',
            'browser' => 'curl',
            'operatingSystem' => 'Linux',
            'iPAddressInfo' => [
                'organisation' => 'Acme',
                'country' => 'Ireland',
                'city' => 'Dublin',
                'continent' => 'Europe',
                'latitude' => '123',
                'longitude' => '123'
            ]
        ];

        $this->assertSame($expected, $this->clientInfoHandler->processSearchTerm('me'));
    }
}
