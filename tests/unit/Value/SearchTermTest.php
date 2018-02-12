<?php

namespace Test\Value;

use CliFyi\Value\SearchTerm;
use PHPUnit\Framework\TestCase;

class SearchTermTest extends TestCase
{
    const TEST_SEARCH_TERM = 'This-Is-A-test';

    /** @var SearchTerm */
    private $searchTerm;

    protected function setUp()
    {
        parent::setUp();

        $this->searchTerm = new SearchTerm(self::TEST_SEARCH_TERM);
    }

    public function testToLowerCase()
    {
        $this->assertSame(strtolower(self::TEST_SEARCH_TERM), $this->searchTerm->toLowerCaseString());
    }

    public function testToUpperCase()
    {
        $this->assertSame(strtoupper(self::TEST_SEARCH_TERM), $this->searchTerm->toUpperCaseString());
    }

    public function testToOriginal()
    {
        $this->assertSame(self::TEST_SEARCH_TERM, $this->searchTerm->toString());
    }
}
