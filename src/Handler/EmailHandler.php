<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use CliFyi\Transformer\TransformerInterface;
use CliFyi\Value\SearchTerm;
use EmailValidation\EmailValidatorFactory as EmailDataExtractor;
use Psr\SimpleCache\CacheInterface;

class EmailHandler extends AbstractHandler
{
    /** @var EmailDataExtractor */
    private $emailDataExtractor;

    /**
     * @param CacheInterface $cache
     * @param TransformerInterface $transformer
     * @param EmailDataExtractor $emailDataExtractor
     */
    public function __construct(
        CacheInterface $cache,
        TransformerInterface $transformer,
        EmailDataExtractor $emailDataExtractor
    ) {
        parent::__construct($cache, $transformer);

        $this->emailDataExtractor = $emailDataExtractor;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Email Address Query';
    }

    /**
     * @param SearchTerm $value
     *
     * @return bool
     */
    public static function isHandlerEligible(SearchTerm $value): bool
    {
        return filter_var($value->toString(), FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param SearchTerm $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(SearchTerm $searchTerm): array
    {
        $emailData = $this->emailDataExtractor->create($searchTerm->toString())->getValidationResults();

        if ($emailData->hasResults()) {
            return $emailData->asArray();
        }

        return [];
    }
}
