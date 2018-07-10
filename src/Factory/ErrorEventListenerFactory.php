<?php

namespace Zend\Mvc\ErrorInterceptor\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\ErrorInterceptor\Common\Enums\Configuration;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorEventListenerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $globalConfiguration = $container->get('ApplicationConfig');
        $configuration = [];

        if (array_key_exists(Configuration::ERROR_LOGGING, $globalConfiguration)){
            $configuration = $globalConfiguration[Configuration::ERROR_LOGGING][$requestedName];
        }

        return new $requestedName($configuration);
    }
}