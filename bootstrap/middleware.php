<?php

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

//$filesystemAdapter = new Local(__DIR__ . '/../cache');
//$filesystem = new Filesystem($filesystemAdapter);
//
//$cachePool = new FilesystemCachePool($filesystem);
//
//$app->add(new \WhatApp\Middleware\CacheMiddleware($cachePool));
$app->add(CliFyi\Middleware\TrailingSlash::class);

