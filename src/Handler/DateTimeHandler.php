<?php

namespace CliFyi\Handler;

use CliFyi\Transformer\TransformerInterface;
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
            'Day' => date('d'),
            'Month' => date('m'),
            'Year' => date('Y'),
            'Hour' => date('H'),
            'Minutes' => date('m'),
            'Seconds' => date('s'),
            'Day Name' => date('l'),
            'Month Name' => date('F'),
            'AM/PM' => date('a'),
            'Unix Epoch' => time(),
            'Formatted Date' => date('r')
        ];
    }
}
