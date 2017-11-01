<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use Psr\SimpleCache\CacheInterface;
use CliFyi\Service\Media\MediaExtractorInterface;
use CliFyi\Transformer\TransformerInterface;

class MediaHandler extends AbstractHandler
{
    /** @var MediaExtractorInterface */
    private $mediaExtractor;

    /** @var string */
    private $handlerName = 'Media';

    /**
     * @param CacheInterface $cache
     * @param TransformerInterface $transformer
     * @param MediaExtractorInterface $mediaExtractor
     */
    public function __construct(
        CacheInterface $cache,
        TransformerInterface $transformer,
        MediaExtractorInterface $mediaExtractor
    ) {
        parent::__construct($cache, $transformer);

        $this->mediaExtractor = $mediaExtractor;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return $this->handlerName;
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return filter_var($searchQuery, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
    {
        if ($extractedData = $this->mediaExtractor->extract($searchQuery)) {
            $this->setHandlerName($extractedData['providerName']);

            return $extractedData;
        }

        return [];
    }

    /**
     * @param string $providerName
     */
    private function setHandlerName(string $providerName): void
    {
        $this->handlerName = ucwords($providerName) . ' Url';
    }
}
