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
            'Common Name' => $data['name']['common'],
            'Official Name' => $data['name']['official'],
            'Top Level Domain' => $data['tld'][0],
            'Currency' => $data['currency'][0],
            'Calling Code' => '+' . $data['callingCode'][0],
            'CapitalCity' => $data['capital'],
            'Region' => $data['region'],
            'Sub Region' => $data['subregion'],
            'Latitude' => $data['latlng'][0],
            'Longitude' => $data['latlng'][1],
            'Demonym' => $data['demonym'],
            'Is Landlocked?' => $data['landlocked'] ? 'Yes' : 'No',
            'Area km²' => $data['area'],
            'Official Languages' => array_values($data['languages'])
        ];
    }
}
