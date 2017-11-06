<?php

declare(strict_types=1);

namespace CliFyi\Handler;

class DomainNameHandler extends AbstractHandler
{
    private const DOMAIN_REGEX = '/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/';
    private const DIG_QUERY = 'dig +nocmd %s %s +multiline +noall +answer';
    private const WHOIS_QUERY = 'whois %s';

    private const DNS_TYPES = [
        'DNSKEY',
        'MX',
        'A',
        'AAAA',
        'NS',
        'SOA',
        'TXT',
    ];

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
        $data['whois'] = explode(PHP_EOL, shell_exec(sprintf(self::WHOIS_QUERY, escapeshellarg(trim($searchQuery)))));
        $data['dns'] =  $this->fetchDnsRecords($searchQuery);

        return $data;
    }

    /**
     * @param string $domain
     *
     * @return array
     */
    private function fetchDnsRecords(string $domain): array
    {
        $data = [];
        foreach (self::DNS_TYPES as $dnsType) {
            $data[] =  shell_exec(sprintf(self::DIG_QUERY, escapeshellarg(trim($domain)), $dnsType));
        }

        return explode(PHP_EOL, implode('', $data));
    }
}
