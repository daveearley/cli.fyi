<?php

namespace Test\Handler;

use CliFyi\Handler\EmailHandler;
use CliFyi\Transformer\EmailDataTransformer;
use EmailValidation\EmailValidator;
use EmailValidation\EmailValidatorFactory;
use EmailValidation\ValidationResults;
use Mockery;

class EmailHandlerTest extends BaseHandlerTestCase
{
    /** @var EmailDataTransformer|Mockery\MockInterface */
    private $emailTransformer;

    /** @var  EmailValidatorFactory|Mockery\MockInterface */
    private $emailDataExtractor;

    /** @var EmailHandler */
    private $emailHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->emailTransformer = Mockery::mock(EmailDataTransformer::class);
        $this->emailDataExtractor = Mockery::mock('alias:' . EmailValidatorFactory::class);
        $this->emailHandler = new EmailHandler($this->cache, $this->emailTransformer, $this->emailDataExtractor);
    }

    public function testGetHandlerName()
    {
        $this->assertSame('Email Address Query', $this->emailHandler->getHandlerName());
    }

    public function testProcessSearchTerms()
    {
        $expected = [
            'Valid Email' => true
        ];

        $emailValidator = Mockery::mock(EmailValidator::class);
        $validationResults = Mockery::mock(ValidationResults::class);

        $this->emailDataExtractor
            ->shouldReceive('create', 'dave@test.com')
            ->andReturn($emailValidator);

        $emailValidator
            ->shouldReceive('getValidationResults')
            ->andReturn($validationResults);

        $validationResults
            ->shouldReceive('hasResults')
            ->andReturn(true);

        $validationResults
            ->shouldReceive('asArray')
            ->andReturn($expected);

        $actual = $this->emailHandler->processSearchTerm('dave@test.com');

        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider eligibleHandlerDataProvider
     *
     * @param mixed $actual
     * @param bool $expected
     */
    public function testIsEligibleHandler($actual, $expected)
    {
        $this->assertSame(EmailHandler::isHandlerEligible($actual), $expected);
    }

    /**
     * @return array
     */
    public function eligibleHandlerDataProvider()
    {
        return [
            ['dave@email.com', true],
            ['email@something.ie', true],
            ['CAPS@something.ie', true],
            ['dave+hello@yahoo.com', true],
            ['@notanemail.com', false],
            ['---^@ yahoo.com', false],
            ['dave+hello @ yahoo.com', false]
        ];
    }
}
