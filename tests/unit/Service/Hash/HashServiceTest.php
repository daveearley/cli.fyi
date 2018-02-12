<?php

namespace Test\Service\Hash;

use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Service\Hash\HasherService;
use PHPUnit\Framework\TestCase;

class HashServiceTest extends TestCase
{
    /** @var HasherInterface */
    private $hashService;

    protected function setUp()
    {
        parent::setUp();

        $this->hashService = new HasherService();
    }

    public function testHashValuesReturned()
    {
        $expectedKeys  = [
            'md4',
            'md5',
            'sha1'
        ];

        $actualResult = $this->hashService->getHashValuesFromString('test string');

        foreach ($expectedKeys as $expectedKey) {
            $this->assertTrue(array_key_exists($expectedKey, $actualResult));
        }
    }
}
