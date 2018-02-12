<?php

namespace Test\Handler;

use CliFyi\Handler\EmojiHandler;
use CliFyi\Value\SearchTerm;

class EmojiHandlerTest extends BaseHandlerTestCase
{
    /** @var EmojiHandler */
    private $emojiHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->emojiHandler = new EmojiHandler($this->cache);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Popular Emojis', $this->emojiHandler->getHandlerName());
    }

    public function testIsHandlerEligibleForValidKeywords()
    {
        foreach (EmojiHandler::EMOJI_KEYWORDS as $keyword) {
            $this->assertTrue(EmojiHandler::ishandlerEligible((new SearchTerm($keyword))));
        }
    }

    public function testIsHandlerEligibleForInvalidKeywords()
    {
        foreach (['not', 'valid', 'keywords'] as $keyword) {
            $this->assertFalse(EmojiHandler::ishandlerEligible((new SearchTerm($keyword))));
        }
    }

    public function testProcessSearchTerm()
    {
        $data = $this->emojiHandler->processSearchTerm((new SearchTerm('emoji')));
        $this->assertArrayHasKey('rofl', $data);
    }
}
