<?php

namespace Test\Handler;

use CliFyi\Handler\HelpHandler;

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
        $this->assertTrue(HelpHandler::isHandlerEligible('help'));
    }

    public function testHandlerName()
    {
        $this->assertSame('Help', $this->helpHandler->getHandlerName());
    }

    public function testProcessSearchTerm()
    {
        $actual = $this->helpHandler->processSearchTerm('help');

        $this->assertArrayHasKey('IP Address Query', $actual);
    }
}
