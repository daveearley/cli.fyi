<?php

namespace Test\Handler;

use CliFyi\Handler\HelpHandler;
use CliFyi\Value\SearchTerm;

class HelpHandlerTest extends BaseHandlerTestCase
{
    /** @var HelpHandler */
    private $helpHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->helpHandler = new HelpHandler($this->cache);
    }

    public function testIsHandlerEligible()
    {
        $this->assertTrue(HelpHandler::ishandlerEligible((new SearchTerm('help'))));
    }

    public function testHandlerName()
    {
        $this->assertSame('Help', $this->helpHandler->getHandlerName());
    }

    public function testProcessSearchTerm()
    {
        $actual = $this->helpHandler->processSearchTerm((new SearchTerm('help')));

        $this->assertArrayHasKey('IP Address Query', $actual);
    }
}
