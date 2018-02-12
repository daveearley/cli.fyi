<?php

namespace Test\Factory;

use CliFyi\Factory\HandlerFactory;
use CliFyi\Handler\ClientInformationHandler;
use CliFyi\Handler\CountryHandler;
use CliFyi\Handler\CryptoCurrencyHandler;
use CliFyi\Handler\DateTimeHandler;
use CliFyi\Handler\DomainNameHandler;
use CliFyi\Handler\EmojiHandler;
use CliFyi\Handler\HashHandler;
use CliFyi\Handler\HelpHandler;
use CliFyi\Handler\IpAddressHandler;
use CliFyi\Handler\MediaHandler;
use CliFyi\Handler\ProgrammingLanguageHandler;
use CliFyi\Service\Client\ClientParser;
use CliFyi\Service\CryptoCurrency\CryptoComparePriceFetcher;
use CliFyi\Service\DomainName\DomainNameServiceProvider;
use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Service\IpAddress\GeoIpProvider;
use CliFyi\Service\Media\MediaExtractor;
use CliFyi\Transformer\CountryDataTransformer;
use CliFyi\Transformer\DomainNameDataTransformer;
use CliFyi\Transformer\MediaDataTransformer;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class HandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|Mockery\MockInterface */
    private $container;

    /** @var HandlerFactory */
    private $handlerFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->container = Mockery::mock(ContainerInterface::class);
        $this->handlerFactory = new HandlerFactory($this->container);
    }

    /**
     * @param string $objectName
     * @param array $expectedArguments
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @dataProvider handlerFactoryDataProvider
     */
    public function testCreate($objectName, $expectedArguments)
    {
        foreach ($expectedArguments as $expectedArgument) {
            $mock = Mockery::mock($expectedArgument);
            $this->container
                ->shouldReceive('get')
                ->with($expectedArgument)
                ->andReturn($mock);
        }

        $actual = $this->handlerFactory->create($objectName);
        $this->assertInstanceOf($objectName, $actual);
    }

    /**
     * @expectedException CliFyi\Exception\InvalidHandlerException
     */
    public function testCreateWithInvalidString()
    {
        $this->handlerFactory->create('not a valid handler');
    }

    public function testGetAvailableHandler()
    {
        $this->assertContains(DateTimeHandler::class, $this->handlerFactory->getAvailableHandlers());
    }

    /**
     * @return array
     */
    public function handlerFactoryDataProvider(): array
    {
        return [
            [
                IpAddressHandler::class,
                [GeoIpProvider::class, CacheInterface::class]
            ],
            [
                ClientInformationHandler::class,
                [ClientParser::class, GeoIpProvider::class, CacheInterface::class]
            ],
            [
                DomainNameHandler::class,
                [DomainNameDataTransformer::class, DomainNameServiceProvider::class, CacheInterface::class]
            ],
            [
                EmojiHandler::class,
                [CacheInterface::class]
            ],
            [
                MediaHandler::class,
                [MediaDataTransformer::class, MediaExtractor::class, CacheInterface::class]
            ],
            [
                DateTimeHandler::class,
                [CacheInterface::class]
            ],
            [
                DateTimeHandler::class,
                [CacheInterface::class]
            ],
            [
                CryptoCurrencyHandler::class,
                [CacheInterface::class, CryptoComparePriceFetcher::class]
            ],
            [
                CountryHandler::class,
                [CacheInterface::class, CountryDataTransformer::class]
            ],
            [
                ProgrammingLanguageHandler::class,
                [CacheInterface::class]
            ],
            [
                HelpHandler::class,
                [CacheInterface::class]
            ],
            [
                HashHandler::class,
                [CacheInterface::class, HasherInterface::class]
            ],
        ];
    }
}
