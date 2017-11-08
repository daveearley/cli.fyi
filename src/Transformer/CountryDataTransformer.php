<?php

declare(strict_types=1);

namespace CliFyi\Transformer;

class CountryDataTransformer implements TransformerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function transform(array $data): array
    {
        return [
            'commonName' => $data['name']['common'],
            'officialName' => $data['name']['official'],
            'topLevelDomain' => $data['tld'][0],
            'currency' => $data['currency'][0],
            'callingCode' => '+' . $data['callingCode'][0],
            'capitalCity' => $data['capital'],
            'region' => $data['region'],
            'subRegion' => $data['subregion'],
            'latitude' => $data['latlng'][0],
            'longitude' => $data['latlng'][1],
            'demonym' => $data['demonym'],
            'isLandlocked' => $data['landlocked'] ? 'Yes' : 'No',
            'areaKm' => $data['area'],
            'officialLanguages' => implode(',', array_values($data['languages']))
        ];
    }
}
