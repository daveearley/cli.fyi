<?php

namespace Test\Handler;

use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

abstract class BaseHandlerTestCase extends TestCase
{
    /** @var CacheInterface */
    protected $cache;

    protected function setUp()
    {
        parent::setUp();

        $this->cache = Mockery::mock(CacheInterface::class);
    }
}
