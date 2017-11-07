<?php

namespace Test\Handler;

use CliFyi\Handler\CountryHandler;

class CountryHandlerTest extends BaseHandlerTestCase
{
    /** @var CountryHandler */
    private $countryHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->countryHandler = new CountryHandler($this->cache);
    }

    public function testGetName()
    {
        $this->assertSame('Country Query', $this->countryHandler->getHandlerName());
    }

    public function testProcessSearchTerm()
    {
        $data = $this->countryHandler->processSearchTerm('Ireland');
        $this->assertSame('Ireland', $data['name']['common']);
    }

    public function testIsEligibleCountryForValidCountry()
    {
        $this->assertTrue(CountryHandler::isHandlerEligible('Ireland'));
    }

    public function testIsEligibleCountryForInvalidCountry()
    {
        $this->assertFalse(CountryHandler::isHandlerEligible('Magicland'));
    }

    public function testGetData()
    {
        $data = $this->countryHandler->processSearchTerm('Ireland');
        $this->assertArrayHasKey('name', $data);
    }
}
