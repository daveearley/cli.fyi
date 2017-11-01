<?php

declare(strict_types=1);

namespace CliFyi\Transformer;

class EmailDataTransformer implements TransformerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data): array
    {
        $transformed = [
            'valid_format' => $data['valid_format'],
            'valid_mx_records' => $data['valid_mx_records'],
            'free_email_provider' => $data['free_email_provider'],
            'disposable_email_provider' => $data['disposable_email_provider'],
            'role_or_business_email' => $data['role_or_business_email'],
            'valid_host' => $data['valid_host']
        ];

        if ($data['possible_email_correction']) {
            $transformed['possible_email_correction'] = $data['possible_email_correction'];
        }

        return $transformed;
    }
}
