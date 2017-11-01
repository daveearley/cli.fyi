<?php

declare(strict_types=1);

namespace CliFyi;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;

class CliFyi extends App
{
    /** @var array */
    private $appConfig;

    /**
     * @param array $appConfig
     */
    public function __construct(array $appConfig)
    {
        $this->appConfig = $appConfig;
        parent::__construct();
    }

    /**
     * @param ContainerBuilder $builder
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions($this->appConfig);
    }
}
