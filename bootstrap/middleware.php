<?php

use CliFyi\Middleware\keenTrackingMiddleware;

$app->add(CliFyi\Middleware\TrailingSlash::class);

if ($keenProjectId = getenv('KEEN_PROJECT_ID') && $keenWriteId = getenv('KEEN_WRITE_ID')) {
    $app->add(new keenTrackingMiddleware($keenProjectId, $keenWriteId));
}

