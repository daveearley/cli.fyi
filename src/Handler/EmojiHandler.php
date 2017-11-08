<?php

declare(strict_types=1);

namespace CliFyi\Handler;

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
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return in_array(trim($searchQuery), self::EMOJI_KEYWORDS, true);
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
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
