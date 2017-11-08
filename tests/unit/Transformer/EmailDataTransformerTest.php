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
            'validMxRecords' => $data['valid_mx_records'],
            'freeProvider' => $data['free_email_provider'],
            'disposableEmail' => $data['disposable_email_provider'],
            'businessOrRoleEmail' => $data['role_or_business_email'],
            'validHost' => $data['valid_host']
        ];

        $actual = (new EmailDataTransformer())->transform($data);

        $this->assertSame($expected, $actual);
    }
}
