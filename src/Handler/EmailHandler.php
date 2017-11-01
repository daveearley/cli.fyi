<?php

declare(strict_types=1);

namespace CliFyi\Handler;

use EmailValidation\EmailValidatorFactory as EmailDataExtractor;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Transformer\TransformerInterface;

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
        return 'Email Query';
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isHandlerEligible(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param string $searchTerm
     *
     * @return array
     */
    public function processSearchTerm(string $searchTerm): array
    {
        $emailData = $this->emailDataExtractor->create($searchTerm)->getValidationResults();

        if ($emailData->hasResults()) {
            return $emailData->asArray();
        }

        return [];
    }
}
