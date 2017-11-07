<?php

namespace CliFyi\Service\DomainName;

class DomainNameServiceProvider implements DomainNameServiceProviderInterface
{
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
     * @param string $hostName
     *
     * @return array
     */
    public function getDnsData(string $hostName): array
    {
        $data = [];
        foreach (self::DNS_TYPES as $dnsType) {
            if ($dnsResult = shell_exec(sprintf(self::DIG_QUERY, escapeshellarg(trim($hostName)), $dnsType))) {
                $data[] =  $dnsResult;
            }
        }

        return explode(PHP_EOL, implode('', $data));
    }

    /**
     * @param string $hostName
     *
     * @return array
     */
    public function getWhoisData(string $hostName): array
    {
        $whoisResult = shell_exec(sprintf(self::WHOIS_QUERY, escapeshellarg(trim($hostName))));

        return $whoisResult ? explode(PHP_EOL, $whoisResult) : [];
    }

    /**
     * @return array
     */
    public static function getDnsTypes()
    {
        return self::DNS_TYPES;
    }
}
