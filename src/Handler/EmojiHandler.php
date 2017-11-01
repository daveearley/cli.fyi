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
        return 'Emoji';
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
            'Hugging face' => '🤗',
            'Tears of joy' => '😂',
            'Grinning Face' => '😀',
            'ROFL' => '🤣',
            'Smiling' => '😊',
            'Tongue out' => '😋',
            'Kissing Face' => '😘',
            'Thinking' => '🤔',
            'Neutral Face' => '😐'
        ];
    }
}
