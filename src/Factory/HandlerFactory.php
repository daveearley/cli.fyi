<?php

declare(strict_types=1);

namespace CliFyi\Factory;

use CliFyi\Handler\IpAddressHandler;
use CliFyi\Service\IpAddress\GeoIpProvider;
use EmailValidation\EmailValidatorFactory;
use GuzzleHttp\Client;
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

class HandlerFactory
{
    /** @var array */
    private static $availableHandlers = [
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

    /**
     * HandlerFactory constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $handlerName
     *
     * @return AbstractHandler
     */
    public function create(string $handlerName): AbstractHandler
    {
        switch ($handlerName) {
            case EmailHandler::class:
                return new EmailHandler(
                    $this->cache,
                    new EmailDataTransformer(),
                    new EmailValidatorFactory()
                );
            case CountryHandler::class:
                return new CountryHandler($this->cache, new CountryDataTransformer());
            case DomainNameHandler::class:
                return new DomainNameHandler($this->cache, new DomainNameDataTransformer());
            case MediaHandler::class:
                return new MediaHandler(
                    $this->cache,
                    new MediaDataTransformer(),
                    new MediaExtractor()
                );
            case EmojiHandler::class:
                return new EmojiHandler($this->cache);
            case ProgrammingLanguageHandler::class:
                return new ProgrammingLanguageHandler($this->cache);
            case CryptoCurrencyHandler::class:
                return new CryptoCurrencyHandler($this->cache, new CryptoComparePriceFetcher(new Client()));
            case ClientInformationHandler::class:
                return new ClientInformationHandler(new GeoIpProvider(), $this->cache);
            case IpAddressHandler::class:
                return new IpAddressHandler(new GeoIpProvider(), $this->cache);
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
