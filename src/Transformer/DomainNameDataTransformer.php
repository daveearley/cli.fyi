<?php

declare(strict_types=1);

namespace CliFyi\Transformer;

class DomainNameDataTransformer implements TransformerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data): array
    {
        return [
            'dns' => $this->transformDnsData($data),
            'whois' => $this->transformWhoisData($data)
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function transformWhoisData(array $data): array
    {
        $whoisData = array_map(function ($whoisLine) {
            return trim($whoisLine);
        }, $data['whois']);

        $whoisData = array_values(array_filter($whoisData, function ($whoisLine) {
            return !empty($whoisLine);
        }));

        return $whoisData;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function transformDnsData(array $data): array
    {
        $dnsData = array_map(function ($dnsLine) {
            return str_replace("\t", " ", $dnsLine);
        }, $data['dns']);

        $dnsData = array_values(array_filter($dnsData, function ($dnsLine) {
            return !empty($dnsLine);
        }));

        return $dnsData;
    }
}
