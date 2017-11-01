<?php

declare(strict_types=1);

namespace CliFyi\Service\Media;

interface MediaExtractorInterface
{
    /**
     * @param string $url
     *
     * @return array
     */
    public function extract(string $url): array;
}
