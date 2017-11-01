<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Cache\RedisAdapter;
use CliFyi\Factory\HandlerFactory;

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
            ->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/logs.log', 30, Logger::DEBUG));
    },
    RedisAdapter::class => \DI\object(RedisAdapter::class)->constructor(\DI\get(Client::class)),
    CacheInterface::class => \DI\object(RedisAdapter::class),
    HandlerFactory::class => \DI\object()->constructor(\DI\get(CacheInterface::class)),
    'errorHandler' => function() {
        return new CliFyi\ErrorHandler\ErrorHandler();
    }
];
