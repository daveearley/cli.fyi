<?php

namespace Test\Handler;

use CliFyi\Handler\DomainNameHandler;
use CliFyi\Service\DomainName\DomainNameServiceProviderInterface;
use CliFyi\Value\SearchTerm;
use Mockery;
use Mockery\MockInterface;

class DomainNameHandlerTest extends BaseHandlerTestCase
{
    /** @var DomainNameServiceProviderInterface|MockInterface */
    private $serviceProvider;

    /** @var DomainNameHandler */
    private $domainNameHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->serviceProvider = Mockery::mock(DomainNameServiceProviderInterface::class);
        $this->domainNameHandler = new DomainNameHandler($this->serviceProvider, $this->cache, $this->transformer);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Domain Name Information', $this->domainNameHandler->getHandlerName());
    }

    public function testProcessSearchTerm()
    {
        $domain = 'google.com';
        $expectedWhois = [
            'some who is info'
        ];
        $expectedDns = [
            'some DNS is info'
        ];

        $expectedResult = [
            'whois' => $expectedWhois,
            'dns' => $expectedDns
        ];

        $this->serviceProvider
            ->shouldReceive('getWhoisData', $domain)
            ->andReturn($expectedWhois);

        $this->serviceProvider
            ->shouldReceive('getDnsData', $domain)
            ->andReturn($expectedDns);

        $actual = $this->domainNameHandler->processSearchTerm((new SearchTerm($domain)));

        $this->assertSame($expectedResult, $actual);
    }

    /**
     * @dataProvider eligibleHandlerDataProvider
     *
     * @param mixed $actual
     * @param bool $expected
     */
    public function testIsEligibleHandler($actual, $expected)
    {
        $this->assertSame(DomainNameHandler::ishandlerEligible((new SearchTerm($actual))), $expected);
    }

    /**
     * @return array
     */
    public function eligibleHandlerDataProvider()
    {
        return [
            ['dave.com', true],
            ['cli.fyi', true],
            ['CAPS.com', true],
            ['cli.fyi.com', true],
            ['dave+hello@yahoo.com', false],
            ['@notanemail.com', false],
            ['---^@ yahoo.com', false],
            [1234, false]
        ];
    }
}
