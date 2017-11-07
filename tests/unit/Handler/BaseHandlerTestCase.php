<?php

namespace Test\Handler;

use CliFyi\Transformer\TransformerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

abstract class BaseHandlerTestCase extends TestCase
{
    /** @var CacheInterface|Mockery\MockInterface */
    protected $cache;

    /** @var TransformerInterface|Mockery\MockInterface */
    protected $transformer;

    protected function setUp()
    {
        parent::setUp();

        $this->cache = Mockery::mock(CacheInterface::class);
        $this->transformer = Mockery::mock(TransformerInterface::class);
    }
}
