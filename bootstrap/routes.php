<?php

use CliFyi\Controller\ApiController;
use CliFyi\Controller\HomePageController;

$app->get('/', HomePageController::class);
$app->get('/{searchQuery}[/{additionalParams:.*}]', ApiController::class);
