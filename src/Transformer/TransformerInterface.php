<?php

declare(strict_types=1);

namespace CliFyi\Transformer;

interface TransformerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data): array;
}
