<?php

declare(strict_types=1);

namespace CliFyi\Handler;

class DomainNameHandler extends AbstractHandler
{
    private const DOMAIN_REGEX = '/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/';
    private const DIG_QUERY = 'dig +nocmd %s ANY +multiline +noall +answer';
    private const WHOIS_QUERY = 'whois %s';

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return 'Domain Name';
    }

    /**
     * @param string $searchQuery
     *
     * @return bool
     */
    public static function isHandlerEligible(string $searchQuery): bool
    {
        return preg_match(self::DOMAIN_REGEX, trim($searchQuery)) === 1;
    }

    /**
     * @param string $searchQuery
     *
     * @return array
     */
    public function processSearchTerm(string $searchQuery): array
    {
        $escapedQuery = escapeshellarg(trim($searchQuery));
        $data['whois'] = explode(PHP_EOL, shell_exec(sprintf(self::WHOIS_QUERY, $escapedQuery)));
        $data['dns'] =  shell_exec(sprintf(self::DIG_QUERY, $escapedQuery));

        return $data;
    }
}
