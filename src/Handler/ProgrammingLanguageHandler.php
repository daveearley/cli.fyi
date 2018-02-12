<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Value\SearchTerm;

class ProgrammingLanguageHandler extends AbstractHandler
{
    const PROGRAMMING_LANGUAGE_DATA_LOCATION = __DIR__ . '/../../data/programming_language_data.php';

    private $handlerName = 'Programming Language Links';

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
        return isset(self::getProgrammingLanguageData()[$searchQuery->toLowerCaseString()]);
    }

    /**
     * @param SearchTerm $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchQuery): array
    {
        $this->handlerName = strtoupper($searchQuery->toString()) . ' Query';

        return self::getProgrammingLanguageData()[$searchQuery->toLowerCaseString()];
    }

    /**
     * @return array
     */
    private static function getProgrammingLanguageData(): array
    {
        return include self::PROGRAMMING_LANGUAGE_DATA_LOCATION;
    }
}
