<?php

namespace Test\Handler;

use CliFyi\Handler\IpAddressHandler;
use CliFyi\Service\IpAddress\IpAddressInfoProviderInterface;
use Mockery;
use Mockery\MockInterface;

class IpAddressHandlerTest extends BaseHandlerTestCase
{
    /** @var IpAddressInfoProviderInterface|MockInterface */
    private $ipAddressService;

    /** @var IpAddressHandler */
    private $ipHandler;

    public function setUp()
    {
        parent::setUp();

        $this->ipAddressService = Mockery::mock(IpAddressInfoProviderInterface::class);
        $this->ipHandler = new IpAddressHandler($this->ipAddressService, $this->cache);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('IP Address', $this->ipHandler->getHandlerName());
    }

    /**
     * @dataProvider eligibleHandlerDataProvider
     *
     * @param mixed $actual
     * @param bool $expected
     */
    public function testIsEligibleHandler($actual, $expected)
    {
        $this->assertSame(IpAddressHandler::isHandlerEligible($actual), $expected);
    }

    public function testProcessSearchTerm()
    {
        $this->ipAddressService->shouldReceive('setIpAddress', '8.8.8.8')->andReturn();
        $this->ipAddressService->shouldReceive('getOrganisation')->andReturn('Google');
        $this->ipAddressService->shouldReceive('getCountry')->andReturn('Ireland');
        $this->ipAddressService->shouldReceive('getCountryCode')->andReturn('IE');
        $this->ipAddressService->shouldReceive('getCity')->andReturn('Dublin');
        $this->ipAddressService->shouldReceive('getContinent')->andReturn('Europe');
        $this->ipAddressService->shouldReceive('getLatitude')->andReturn('123');
        $this->ipAddressService->shouldReceive('getLongitude')->andReturn('123');

        $this->assertSame([
            'organisation' => 'Google',
            'country' => 'Ireland',
            'countryCode' => 'IE',
            'city' => 'Dublin',
            'continent' => 'Europe',
            'latitude' => '123',
            'longitude' => '123'
        ], $this->ipHandler->processSearchTerm('8.8.8.8'));
    }

    public function testPrivateIpAddress()
    {
        $this->ipAddressService->shouldReceive('setIpAddress', '192.168.2.1')->andReturn();
        $this->ipAddressService->shouldReceive('getOrganisation')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCountry')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCountryCode')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCity')->andReturn(null);
        $this->ipAddressService->shouldReceive('getContinent')->andReturn(null);
        $this->ipAddressService->shouldReceive('getLatitude')->andReturn(null);
        $this->ipAddressService->shouldReceive('getLongitude')->andReturn(null);

        $this->assertSame(
            [
                'isIpInPrivateRange' => true
            ],
            $this->ipHandler->processSearchTerm('192.168.2.1')
        );
    }

    public function testReservedIpAddress()
    {
        $this->ipAddressService->shouldReceive('setIpAddress', '0.0.0.6')->andReturn();
        $this->ipAddressService->shouldReceive('getOrganisation')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCountry')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCountryCode')->andReturn(null);
        $this->ipAddressService->shouldReceive('getCity')->andReturn(null);
        $this->ipAddressService->shouldReceive('getContinent')->andReturn(null);
        $this->ipAddressService->shouldReceive('getLatitude')->andReturn(null);
        $this->ipAddressService->shouldReceive('getLongitude')->andReturn(null);


        $this->assertSame(
            [
                'isIpInReservedRange' => true
            ],
            $this->ipHandler->processSearchTerm('0.0.0.6')
        );
    }

    /**
     * @return array
     */
    public function eligibleHandlerDataProvider()
    {
        return [
            ['8.8.8.8', true],
            ['109.123.1.102', true],
            ['127.0.0.1', true],
            ['192.168.2.1', true],
            ['10.0.0.1', true],
            ['123.2.3', false],
            ['hello.12.3.3', false],
            [12, false]
        ];
    }
}
