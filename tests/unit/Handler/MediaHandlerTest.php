<?php

namespace Test\Handler;

use Essence\Essence;
use Mockery;
use CliFyi\Handler\MediaHandler;
use CliFyi\Transformer\MediaDataTransformer;

class MediaHandlerTest extends BaseHandlerTestCase
{
    /** @var Essence|Mockery */
    private $mediaExtractor;

    /** @var MediaDataTransformer|Mockery */
    private $mediaTransformer;

    /** @var MediaHandler */
    private $mediaHandler;

    protected function setUp()
    {
        parent::setUp();


        $this->mediaExtractor = Mockery::mock(Essence::class);
        $this->mediaTransformer = Mockery::mock(MediaDataTransformer::class);
        $this->mediaHandler = new MediaHandler($this->mediaExtractor, $this->mediaTransformer);
    }

    public function testGetName()
    {
        $this->assertSame('Media', $this->mediaHandler->getName());
    }

    public function testIsEligibleHandler()
    {

    }

    public function eligibleHandlerDataProvider()
    {
        return [
            ['https://www.youtube.com/watch?v=bgmiLMVAG7g', true],
            ['https://youtu.be/meCZ5hWNRFU', true]
            ];
    }



}
