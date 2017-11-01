<?php

declare(strict_types=1);

namespace CliFyi\Handler;

class ClientInformationHandler extends AbstractHandler
{
    private const KEYWORDS = [
        'me',
        'self'
    ];

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Client Query';
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return in_array($searchQuery, self::KEYWORDS, true);
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(string $searchTerm): array
    {
        return [
            'User Agent' => $_SERVER['HTTP_USER_AGENT']
        ];
    }
}
