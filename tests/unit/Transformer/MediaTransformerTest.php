<?php

namespace Test\Transformer;

use CliFyi\Transformer\MediaDataTransformer;
use PHPUnit\Framework\TestCase;

class MediaTransformerTest extends TestCase
{
    /** @var MediaDataTransformer */
    private $mediaTransformer;

    protected function setUp()
    {
        parent::setUp();

        $this->mediaTransformer = new MediaDataTransformer();
    }

    public function testTransformMedia()
    {
        $actual = $this->mediaTransformer->transform([
            'empty' => '',
            'title' => 'some title',
            'tags' => [
                'tag1',
                'tag2'
            ],
            'linkedData' => 'some-data',
            'providerIcons' => 'unwanted icons'
        ]);

        $expected = [
            'title' => 'some title',
            'tags' => 'tag1, tag2'
        ];

        $this->assertSame($expected, $actual);
    }
}
