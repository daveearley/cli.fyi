<?php

use CliFyi\ErrorHandler\ErrorHandler;
use CliFyi\Middleware\GoogleAnalyticsMiddleware;
use CliFyi\Service\CryptoCurrency\CryptoComparePriceFetcher;
use CliFyi\Service\Hash\HasherInterface;
use CliFyi\Service\Hash\HasherService;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Predis\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Cache\RedisAdapter;

return [
    RequestInterface::class => \DI\get('request'),
    ResponseInterface::class => \DI\get('response'),
    Client::class => function () {
        return new Client([
            'scheme' => 'tcp',
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
        ]);
    },
    ClientInterface::class => \DI\object(HttpClient::class),
    LoggerInterface::class => function () {
        return (new Logger('Cli.Fyi Log'))
            ->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/logs.log', 30, Logger::INFO));
    },
    RedisAdapter::class => \DI\object(RedisAdapter::class)->constructor(\DI\get(Client::class)),
    CacheInterface::class => \DI\object(RedisAdapter::class),
    CryptoComparePriceFetcher::class => \DI\object()->constructor(\DI\get(HttpClient::class)),
    'errorHandler' => \DI\object(ErrorHandler::class)
        ->constructor(\DI\get(LoggerInterface::class), getenv('DEBUG_MODE')),
    'phpErrorHandler' => \DI\object(ErrorHandler::class)
        ->constructor(\DI\get(LoggerInterface::class), getenv('DEBUG_MODE')),
    GoogleAnalyticsMiddleware::class => \DI\object()->constructor(
        \DI\get(ClientInterface::class), \DI\get(LoggerInterface::class),
        \DI\object(\CliFyi\Service\UuidGenerator::class), getenv('GOOGLE_ANALYTICS_ID')
    ),
    HasherInterface::class => DI\object(HasherService::class)
];
