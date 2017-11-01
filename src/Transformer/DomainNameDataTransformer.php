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
        $whoisData = array_map(function ($whoisLine) {
            return trim($whoisLine);
        }, $data['whois']);

        $whoisData = array_values(array_filter($whoisData, function ($whoisLine) {
            return !empty($whoisLine);
        }));

        return [
            'dns' => $data['dns'],
            'whois' => $whoisData
        ];
    }
}
