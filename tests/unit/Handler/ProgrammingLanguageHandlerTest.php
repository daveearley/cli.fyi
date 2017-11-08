<?php

namespace Test\Handler;

use CliFyi\Handler\ProgrammingLanguageHandler;

class ProgrammingLanguageHandlerTest extends BaseHandlerTestCase
{
    /** @var ProgrammingLanguageHandler */
    private $programmingLanguageHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->programmingLanguageHandler = new ProgrammingLanguageHandler($this->cache);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Programming Language Links', $this->programmingLanguageHandler->getHandlerName());
    }

    public function testIsHandlerEligibleForValidKeywords()
    {
        foreach (['PhP', 'php', 'java', 'javascript', 'JAVAscRipt'] as $keyword) {
            $this->assertTrue(ProgrammingLanguageHandler::isHandlerEligible($keyword));
        }
    }

    public function testIsHandlerEligibleForInvalidKeywords()
    {
        foreach (['not', 'valid', 'keywords'] as $keyword) {
            $this->assertFalse(ProgrammingLanguageHandler::isHandlerEligible($keyword));
        }
    }

    public function testProcessSearchTerm()
    {
        $data = $this->programmingLanguageHandler->processSearchTerm('php');
        $this->assertArrayHasKey('documentation', $data);
    }
}
