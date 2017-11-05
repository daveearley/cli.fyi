<?php

namespace Test\Handler;

use CliFyi\Service\Media\MediaExtractorInterface;
use Mockery;
use CliFyi\Handler\MediaHandler;
use CliFyi\Transformer\MediaDataTransformer;

class MediaHandlerTest extends BaseHandlerTestCase
{
    /** @var MediaExtractorInterface|Mockery\MockInterface */
    private $mediaExtractor;

    /** @var MediaDataTransformer|Mockery */
    private $mediaTransformer;

    /** @var MediaHandler */
    private $mediaHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->mediaExtractor = Mockery::mock(MediaExtractorInterface::class);
        $this->mediaTransformer = Mockery::mock(MediaDataTransformer::class);
        $this->mediaHandler = new MediaHandler($this->cache, $this->mediaTransformer, $this->mediaExtractor);
    }

    public function testGetName()
    {
        $this->assertSame('Media', $this->mediaHandler->getHandlerName());
    }

    /**
     * @dataProvider eligibleHandlerDataProvider
     */
    public function testIsEligibleHandler($actual, $expected)
    {
        $this->assertSame(MediaHandler::isHandlerEligible($actual), $expected);
    }

    public function testSetHandler()
    {
        $this->mediaExtractor->shouldReceive('extract', 'http://dave.com')->andReturn([
            'providerName' => 'Dave'
        ]);

        $this->mediaHandler->processSearchTerm('http://dave.com');

        $this->assertSame('Dave URL', $this->mediaHandler->getHandlerName());
    }

    public function testProcessSearchTerm()
    {
        $expected = ['providerName' => 'Testy Mc Test', 'some' => 'data'];
        $this->mediaExtractor->shouldReceive('extract', 'http://dave.com')->andReturn($expected);

        $actual = $this->mediaHandler->processSearchTerm('http://dave.com');

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function eligibleHandlerDataProvider()
    {
        return [
            ['https://www.youtube.com/watch?v=bgmiLMVAG7g', true],
            ['https://youtu.be/meCZ5hWNRFU', true],
            ['https://vimeo.com/213421', true],
            ['vimeo.com/213421', false],
            ['not good', false],
            [12, false]
        ];
    }
}
