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
            'validMxRecords' => $data['valid_mx_records'],
            'freeProvider' => $data['free_email_provider'],
            'disposableEmail' => $data['disposable_email_provider'],
            'businessOrRoleEmail' => $data['role_or_business_email'],
            'validHost' => $data['valid_host']
        ];

        if ($data['possible_email_correction']) {
            $transformed['possibleSpellingCorrection'] = $data['possible_email_correction'];
        }

        return $transformed;
    }
}
