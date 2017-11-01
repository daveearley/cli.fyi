<?php

namespace Test\Handler;

use PHPUnit\Framework\TestCase;
use CliFyi\Handler\CountryHandler;

class CountryHandlerTest extends TestCase
{
    /** @var CountryHandler */
    private $countryHandler;

    protected function setUp()
    {
       $this->countryHandler = new CountryHandler();
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
        $data = $this->countryHandler->getData('Ireland');
        $this->assertArrayHasKey('name', $data);
    }
}
