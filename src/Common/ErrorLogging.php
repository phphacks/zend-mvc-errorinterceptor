<?php

namespace Zend\Mvc\ErrorInterceptor\Common;

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
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

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