<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Value\SearchTerm;

class EmojiHandler extends AbstractHandler
{
    const EMOJI_KEYWORDS = [
        'emoji',
        'emojis',
        'emoticons',
        'smileys'
    ];

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Popular Emojis';
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return in_array($searchQuery->toLowerCaseString(), self::EMOJI_KEYWORDS, true);
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        return [
            'huggingFace' => '🤗',
            'tearsOfJoy' => '😂',
            'grinningFace' => '😀',
            'rofl' => '🤣',
            'smiling' => '😊',
            'tongueOut' => '😋',
            'kissingFace' => '😘',
            'thinking' => '🤔',
            'neutralFace' => '😐'
        ];
    }
}
