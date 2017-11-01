<?php

namespace Test\Handler;

use EmailValidation\EmailValidator;
use EmailValidation\EmailValidatorFactory;
use EmailValidation\ValidationResults;
use Mockery;
use PHPUnit\Framework\TestCase;
use CliFyi\Handler\EmailHandler;
use CliFyi\Transformer\EmailDataTransformer;

class EmailHandlerTest extends TestCase
{
    /** @var EmailDataTransformer|Mockery\MockInterface */
    private $transformer;

    /** @var EmailValidatorFactory|Mockery\MockInterface */
    private $emailDataExtractor;

    /** @var EmailHandler */
    private $emailHandler;

    protected function setUp()
    {
        $this->transformer = Mockery::mock(EmailDataTransformer::class);
        $this->emailDataExtractor = Mockery::mock('alias:' . EmailValidatorFactory::class);
        $this->emailHandler = new EmailHandler($this->transformer, $this->emailDataExtractor);
    }

    public function testGetName()
    {
        $this->assertSame($this->emailHandler->getName(), 'Email');
    }

    public function testGetData()
    {
        $expectedResult = ['results'];
        $emailValidator = Mockery::mock(EmailValidator::class);
        $emailDataResults = Mockery::mock(ValidationResults::class);

        $this->emailDataExtractor
            ->shouldReceive('create')
            ->withArgs(['dave@test.com'])
            ->andReturn($emailValidator);

        $emailValidator
            ->shouldReceive('getValidationResults')
            ->andReturn($emailDataResults);

        $emailDataResults
            ->shouldReceive('hasResults')
            ->andReturn(true);

        $emailDataResults->shouldReceive('asArray')->andReturn($expectedResult);

        $this->transformer->shouldReceive('transform')
            ->withArgs([$expectedResult])
            ->andReturn($expectedResult);

        $actual = $this->emailHandler->getData('dave@test.com');

        $this->assertSame($expectedResult, $actual);
    }

    public function testisHandlerEligibleForValidEmail()
    {
        $this->assertTrue(EmailHandler::isHandlerEligible('dave@test.com'));
    }

    public function testisHandlerEligibleForInalidEmail()
    {
        $this->assertFalse(EmailHandler::isHandlerEligible('dave@ %% test.com'));
    }
}
