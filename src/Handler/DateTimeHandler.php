<?php

namespace CliFyi\Handler;

use CliFyi\Value\SearchTerm;
use Psr\SimpleCache\CacheInterface;

class DateTimeHandler extends AbstractHandler
{
    const KEYWORDS = [
      'time',
      'date',
      'datetime'
    ];

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        parent::__construct($cache);

        $this->disableCache();
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Date/Time Information (UTC)';
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $searchQuery): bool
    {
        return in_array($searchQuery->toLowerCaseString(), self::KEYWORDS, true);
    }

    /**
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchTerm): array
    {
        return [
            'day' => date('d'),
            'month' => date('m'),
            'year' => date('Y'),
            'hour' => date('H'),
            'minutes' => date('i'),
            'seconds' => date('s'),
            'dayName' => date('l'),
            'monthName' => date('F'),
            'amOrPm' => date('a'),
            'unixEpoch' => time(),
            'formattedDate' => date('r')
        ];
    }
}
