<?php

use CliFyi\ErrorHandler\ErrorHandler;
use CliFyi\Service\CryptoCurrency\CryptoComparePriceFetcher;
use GuzzleHttp\Client as HttpClient;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Cache\RedisAdapter;

return [
    Client::class => function (ContainerInterface $container) {
        return new Client([
            'scheme' => 'tcp',
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
        ]);
    },
    LoggerInterface::class => function () {
        return (new Logger('Cli.Fyi Log'))
            ->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/logs.log', 30, Logger::INFO));
    },
    RedisAdapter::class => \DI\object(RedisAdapter::class)->constructor(\DI\get(Client::class)),
    CacheInterface::class => \DI\object(RedisAdapter::class),
    CryptoComparePriceFetcher::class => \DI\object()->constructor(\DI\get(HttpClient::class)),
    'errorHandler' => \DI\object(ErrorHandler::class)
        ->constructor(\DI\get(LoggerInterface::class), getenv('DEBUG_MODE')),
    'phpErrorHandler' =>  \DI\object(ErrorHandler::class)
        ->constructor(\DI\get(LoggerInterface::class), getenv('DEBUG_MODE'))

];
