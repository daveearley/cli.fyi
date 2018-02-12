<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Service\Media\MediaExtractorInterface;
use CliFyi\Transformer\TransformerInterface;
use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

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
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return filter_var($searchQuery->toString(), FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        if ($extractedData = $this->mediaExtractor->extract($searchQuery->toString())) {
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
        $this->handlerName = ucwords($providerName) . ' URL';
    }
}
