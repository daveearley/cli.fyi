<?php

namespace Test\Transformer;

use CliFyi\Transformer\EmailDataTransformer;
use PHPUnit\Framework\TestCase;

class EmailDataTransformerTest extends TestCase
{
    public function testTransform()
    {
        $data = [];
        $data['valid_mx_records'] = true;
        $data['free_email_provider'] = true;
        $data['disposable_email_provider'] = true;
        $data['role_or_business_email'] = false;
        $data['possible_email_correction'] = '';
        $data['valid_host'] = true;

        $expected = [
            'Valid MX Records' => $data['valid_mx_records'],
            'Free Provider' => $data['free_email_provider'],
            'Disposable Email' => $data['disposable_email_provider'],
            'Business/Role Email' => $data['role_or_business_email'],
            'Valid Host' => $data['valid_host']
        ];

        $actual = (new EmailDataTransformer())->transform($data);

        $this->assertSame($expected, $actual);
    }
}
