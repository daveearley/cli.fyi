<?php

declare(strict_types=1);

namespace CliFyi\Service\Media;

use Embed\Adapters\Adapter;
use Embed\Embed;
use Exception;

class MediaExtractor implements MediaExtractorInterface
{
    /** @var Adapter */
    private $extractedData;

    /**
     * @param string $url
     *
     * @return array
     */
    public function extract(string $url): array
    {
        try {
            $this->extractedData = Embed::create($url);
        } catch (Exception $exception) {
            return [];
        }

        return $this->getExtractedValues();
    }

    /**
     * @return array
     */
    private function getExtractedValues(): array
    {
        if ($this->extractedData::check($this->extractedData->getResponse())) {
            return [
                'title' => $this->extractedData->title,
                'description' => $this->extractedData->description,
                'url' => $this->extractedData->url,
                'type' => $this->extractedData->type,
                'tags' => $this->extractedData->tags,
                'images' => $this->extractedData->images,
                'image' => $this->extractedData->image,
                'imageWidth' => $this->extractedData->imageWidth,
                'imageHeight' => $this->extractedData->imageHeight,
                'code' => $this->extractedData->code,
                'width' => $this->extractedData->width,
                'height' => $this->extractedData->height,
                'aspectRatio' => $this->extractedData->aspectRatio,
                'authorName' => $this->extractedData->authorName,
                'authorUrl' => $this->extractedData->authorUrl,
                'providerName' => $this->extractedData->providerName,
                'providerUrl' => $this->extractedData->providerUrl,
                'providerIcons' => $this->extractedData->providerIcons,
                'providerIcon' => $this->extractedData->providerIcon,
                'publishedDate' => $this->extractedData->publishedDate,
                'license' => $this->extractedData->license,
                'linkedData' => $this->extractedData->linkedData,
                'feeds' => $this->extractedData->feeds
            ];
        }
    }
}
