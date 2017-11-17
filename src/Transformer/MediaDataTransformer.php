<?php

declare(strict_types=1);

namespace CliFyi\Transformer;

class MediaDataTransformer implements TransformerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data): array
    {
        $data = array_filter($data, function ($value) {
            return !empty($value);
        });

        if (isset($data['tags'])) {
            $data['tags'] = implode(', ', $data['tags']);
        }

        unset($data['linkedData'], $data['providerIcons']);

        return $data;
    }
}
