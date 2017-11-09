<?php

use CliFyi\Middleware\GoogleAnalyticsMiddleware;
use CliFyi\Middleware\TrailingSlash;
use RKA\Middleware\IpAddress;

$app->add(new TrailingSlash());
$app->add(new IpAddress());

if ($googleAnalyticsId = getenv('GOOGLE_ANALYTICS_ID')) {
    $app->add(GoogleAnalyticsMiddleware::class);
}

