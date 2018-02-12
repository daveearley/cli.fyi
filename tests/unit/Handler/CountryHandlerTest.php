<?php

namespace Test\Handler;

use CliFyi\Handler\CountryHandler;
use CliFyi\Value\SearchTerm;

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
        $data = $this->countryHandler->processSearchTerm((new SearchTerm('Ireland')));
        $this->assertSame('Ireland', $data['name']['common']);
    }

    public function testIsEligibleCountryForValidCountry()
    {
        $this->assertTrue(CountryHandler::ishandlerEligible((new SearchTerm('Ireland'))));
    }

    public function testIsEligibleCountryForValidCountryWithSpaces()
    {
        $this->assertTrue(CountryHandler::ishandlerEligible((new SearchTerm('UNITED-STATES'))));
    }

    public function testIsEligibleCountryForInvalidCountry()
    {
        $this->assertFalse(CountryHandler::ishandlerEligible((new SearchTerm('Magicland'))));
    }

    public function testGetData()
    {
        $data = $this->countryHandler->processSearchTerm((new SearchTerm('Ireland')));
        $this->assertArrayHasKey('name', $data);
    }
}
