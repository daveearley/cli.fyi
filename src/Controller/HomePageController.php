<?php

declare(strict_types=1);

namespace CliFyi\Controller;

class HomePageController
{
    const HOMEPAGE_FILE = __DIR__ . '/../../public/homepage.php';

    public function __invoke()
    {
        return include self::HOMEPAGE_FILE;
    }
}
