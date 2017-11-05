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
        $this->assertSame('Country', $this->countryHandler->getName());
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
