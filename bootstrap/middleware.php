<?php

use CliFyi\Middleware\keenTrackingMiddleware;
use CliFyi\Middleware\TrailingSlash;
use RKA\Middleware\IpAddress;

$app->add(new TrailingSlash());
$app->add(new IpAddress());

if ($keenProjectId = getenv('KEEN_PROJECT_ID') && $keenWriteId = getenv('KEEN_WRITE_ID')) {
    $app->add(new keenTrackingMiddleware($keenProjectId, $keenWriteId));
}

