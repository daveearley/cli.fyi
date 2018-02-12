<?php

namespace Test\Handler;

use CliFyi\Handler\AbstractHandler;
use CliFyi\Handler\HashHandler;
use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Value\SearchTerm;
use Mockery;

class HashHandlerTest extends BaseHandlerTestCase
{
    /** @var HasherInterface|Mockery\MockInterface */
    private $hashService;

    /** @var AbstractHandler */
    private $hashHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->hashService = Mockery::mock(HasherInterface::class);
        $this->hashHandler = new HashHandler($this->cache, $this->hashService);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('String Hash Values For: ', $this->hashHandler->getHandlerName());
    }

    public function testIsHandlerEligibleForValidKeywords()
    {
        $this->assertTrue(HashHandler::isHandlerEligible((new SearchTerm(HashHandler::TRIGGER_KEYWORD))));
    }

    public function testIsHandlerEligibleForInvalidKeywords()
    {
        foreach (['not', 'valid', 'keywords'] as $keyword) {
            $this->assertFalse(HashHandler::ishandlerEligible((new SearchTerm($keyword))));
        }
    }

    public function testParseSearchTerm()
    {
        $expected = [
            'md5' => '...',
            'sha1' => '...'
        ];

        $this->hashService->shouldReceive('getHashValuesFromString')->andReturn($expected);

        $this->assertSame($expected, $this->hashHandler->processSearchTerm((new SearchTerm(HashHandler::TRIGGER_KEYWORD))));
    }
}
