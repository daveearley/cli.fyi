<?php

namespace CliFyi\Value;

class SearchTerm
{
    /** @var string */
    private $searchTerm;

    /**
     * @param string $searchTerm
     */
    public function __construct(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->searchTerm;
    }

    /**
     * @return string
     */
    public function toLowerCaseString(): string
    {
        return strtolower($this->searchTerm);
    }

    /**
     * @return string
     */
    public function toUpperCaseString(): string
    {
        return strtoupper($this->searchTerm);
    }
}
