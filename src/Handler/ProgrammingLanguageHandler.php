<?php

declare(strict_types=1);

namespace CliFyi\Handler;

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
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return isset(self::getProgrammingLanguageData()[trim(strtolower($searchQuery))]);
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
    {
        $this->handlerName = strtoupper($searchQuery) . ' Query';

        return self::getProgrammingLanguageData()[trim(strtolower($searchQuery))];
    }

    /**
     * @return array
     */
    private static function getProgrammingLanguageData(): array
    {
        return include self::PROGRAMMING_LANGUAGE_DATA_LOCATION;
    }
}
