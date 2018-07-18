<?php

namespace Zend\Mvc\ErrorLogger\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorEventListenerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $globalConfiguration = $container->get('ApplicationConfig');

        return new $requestedName($globalConfiguration);
    }
}