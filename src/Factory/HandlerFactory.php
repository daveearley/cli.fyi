<?php

declare(strict_types=1);

namespace CliFyi\Factory;

use CliFyi\Handler\DateTimeHandler;
use CliFyi\Handler\IpAddressHandler;
use CliFyi\Service\IpAddress\GeoIpProvider;
use EmailValidation\EmailValidatorFactory;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Exception\InvalidHandlerException;
use CliFyi\Handler\AbstractHandler;
use CliFyi\Handler\CountryHandler;
use CliFyi\Handler\CryptoCurrencyHandler;
use CliFyi\Handler\ClientInformationHandler;
use CliFyi\Handler\DomainNameHandler;
use CliFyi\Handler\EmailHandler;
use CliFyi\Handler\EmojiHandler;
use CliFyi\Handler\MediaHandler;
use CliFyi\Handler\ProgrammingLanguageHandler;
use CliFyi\Service\CryptoCurrency\CryptoComparePriceFetcher;
use CliFyi\Service\Media\MediaExtractor;
use CliFyi\Transformer\CountryDataTransformer;
use CliFyi\Transformer\DomainNameDataTransformer;
use CliFyi\Transformer\EmailDataTransformer;
use CliFyi\Transformer\MediaDataTransformer;
use WhichBrowser\Parser;

class HandlerFactory
{
    /** @var array */
    private static $availableHandlers = [
        DateTimeHandler::class,
        ClientInformationHandler::class,
        CryptoCurrencyHandler::class,
        ProgrammingLanguageHandler::class,
        EmojiHandler::class,
        EmailHandler::class,
        CountryHandler::class,
        DomainNameHandler::class,
        MediaHandler::class,
        IpAddressHandler::class
    ];

    /** @var CacheInterface */
    private $cache;

    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->cache = $container->get(CacheInterface::class);
    }

    /**
     * @param string $handlerName
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return AbstractHandler
     */
    public function create(string $handlerName): AbstractHandler
    {
        switch ($handlerName) {
            case EmailHandler::class:
                return new EmailHandler(
                    $this->cache,
                    $this->container->get(EmailDataTransformer::class),
                    $this->container->get(EmailValidatorFactory::class)
                );
            case CountryHandler::class:
                return new CountryHandler($this->cache, $this->container->get(CountryDataTransformer::class));
            case DomainNameHandler::class:
                return new DomainNameHandler($this->cache, $this->container->get(DomainNameDataTransformer::class));
            case MediaHandler::class:
                return new MediaHandler(
                    $this->cache,
                    $this->container->get(MediaDataTransformer::class),
                    $this->container->get(MediaExtractor::class)
                );
            case EmojiHandler::class:
                return new EmojiHandler($this->cache);
            case ProgrammingLanguageHandler::class:
                return new ProgrammingLanguageHandler($this->cache);
            case CryptoCurrencyHandler::class:
                return new CryptoCurrencyHandler($this->cache, $this->container->get(CryptoComparePriceFetcher::class));
            case ClientInformationHandler::class:
                return new ClientInformationHandler(
                    $this->container->get(Parser::class),
                    $this->container->get(GeoIpProvider::class),
                    $this->cache
                );
            case IpAddressHandler::class:
                return new IpAddressHandler($this->container->get(GeoIpProvider::class), $this->cache);
            case DateTimeHandler::class:
                return new DateTimeHandler($this->cache);
        }

        throw new InvalidHandlerException(sprintf('%s is not a valid handler name'));
    }

    /**
     * @return string[]
     */
    public function getAvailableHandlers(): array
    {
        return self::$availableHandlers;
    }
}
