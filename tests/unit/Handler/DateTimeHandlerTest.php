<?php

namespace Test\Handler;

use CliFyi\Handler\DateTimeHandler;

class DateTimeHandlerTest extends BaseHandlerTestCase
{
    /** @var DateTimeHandler */
    private $dateTimeHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->dateTimeHandler = new DateTimeHandler($this->cache);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Date/Time Information (UTC)', $this->dateTimeHandler->getHandlerName());
    }

    public function testIsHandlerEligibleForValidKeywords()
    {
        foreach (DateTimeHandler::KEYWORDS as $keyword) {
            $this->assertTrue(DateTimeHandler::isHandlerEligible($keyword));
        }
    }

    public function testIsHandlerEligibleForInvalidKeywords()
    {
        foreach (['not', 'valid', 'keywords'] as $keyword) {
            $this->assertFalse(DateTimeHandler::isHandlerEligible($keyword));
        }
    }

    public function testProcessSearchTerm()
    {
        $data = $this->dateTimeHandler->processSearchTerm('time');
        $this->assertArrayHasKey('day', $data);
    }
}
