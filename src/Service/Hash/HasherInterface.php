<?php

namespace CliFyi\Service\Hash;

interface HasherInterface
{
    /**
     * @param string $stringToHash
     *
     * @return array eg. ['md5' => '86fb269d190d2c85f6e0468ceca42a20', 'sha1' => '...']
     */
    public function getHashValuesFromString(string $stringToHash): array;
}
