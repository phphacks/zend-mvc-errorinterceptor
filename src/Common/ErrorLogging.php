<?php

namespace Zend\Mvc\ErrorLogger\Common;

class ErrorLogging
{
    /**
     * @var string
     */
    public $response;


    /**
     * @var LoggerDefinition[]
     */
    public $loggers = [];

    /**
     * @return LoggerDefinition[]
     */
    public function getLoggers(): array
    {
        return $this->loggers;
    }

    /**
     * @param LoggerDefinition[] $loggers
     */
    public function setLoggers(array $loggers)
    {
        $this->loggers = $loggers;
    }

    public function addLogger(LoggerDefinition $logger)
    {
        $this->loggers[] = $logger;
    }

}