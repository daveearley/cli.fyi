<?php

namespace CliFyi\Service\Hash;

class HasherService implements HasherInterface
{
    /** @var array */
    private $hashedKeyValues = [];

    /**
     * @param string $stringToHash
     *
     * @return array
     */
    public function getHashValuesFromString(string $stringToHash): array
    {
        foreach (hash_algos() as $algo) {
            $hash = hash($algo, $stringToHash, false);
            $this->hashedKeyValues[$algo] = $hash;
        }

        return $this->hashedKeyValues;
    }
}
