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
            'Valid MX Records' => $data['valid_mx_records'],
            'Free Provider' => $data['free_email_provider'],
            'Disposable Email' => $data['disposable_email_provider'],
            'Business/Role Email' => $data['role_or_business_email'],
            'Valid Host' => $data['valid_host']
        ];

        if ($data['possible_email_correction']) {
            $transformed['Possible Spelling Correction'] = $data['possible_email_correction'];
        }

        return $transformed;
    }
}
