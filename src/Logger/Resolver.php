<?php

namespace Zend\Mvc\ErrorInterceptor\Logger;



use Zend\Mvc\ErrorInterceptor\Common\Enums\Configuration;
use Zend\Mvc\MvcEvent;

class Resolver
{

    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    public function getLoggers(MvcEvent $event): array
    {
        if (isset($this->configuration[Configuration::ERROR_LOGGING])
        && isset($this->configuration[Configuration::ERROR_LOGGING][Configuration::LOGGERS])){

            $loggersConfig = $this->configuration[Configuration::ERROR_LOGGING][Configuration::LOGGERS];

            //foreach ($loggersConfig as $loggerConfig)

        }
        return array();

    }
}