<?php

use CliFyi\CliFyi;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/environment.php';

if ($bugsnagKey = getenv('BUGSNAG_KEY')) {
    Bugsnag\Handler::register(Bugsnag\Client::make($bugsnagKey));
}

// Container / General settings
$settings = require __DIR__ . '/../bootstrap/settings.php';
$dependencies = require __DIR__ . '/../bootstrap/dependencies.php';
$appConfiguration = array_merge($settings, $dependencies);

$app = new CliFyi($appConfiguration);

require  __DIR__ . '/../bootstrap/middleware.php';
require  __DIR__ . '/../bootstrap/routes.php';

$app->run();
