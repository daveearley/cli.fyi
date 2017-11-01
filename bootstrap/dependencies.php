<?php

use Predis\Client;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use CliFyi\Cache\RedisAdapter;
use CliFyi\Factory\HandlerFactory;

return [
    Client::class => function(ContainerInterface $container) {
        $redisHost = getenv('REDIS_HOST');
        $redisPort = getenv('REDIS_PORT');

        return new Client([
            'scheme' => 'tcp',
            'host'   => $redisHost,
            'port'   => $redisPort,
        ]);
    },
    RedisAdapter::class => \DI\object(RedisAdapter::class)->constructor(\DI\get(Client::class)),
    CacheInterface::class => \DI\object(RedisAdapter::class),
    HandlerFactory::class => \DI\object()->constructor(\DI\get(CacheInterface::class))
];
