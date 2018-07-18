<?php

namespace Zend\Mvc\ErrorLogger\Common;

use Exception;

class LoggerDefinition
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string[]
     */
    protected $exceptions = [];

    /**
     * @var string[]
     */
    protected $ignored = [];

    /**
     * @var string
     */
    protected $factoryName;

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return string[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * @param string[] $exceptions
     */
    public function setExceptions(array $exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * @return string[]
     */
    public function getIgnored(): array
    {
        return $this->ignored;
    }

    /**
     * @param string[] $ignored
     */
    public function setIgnored(array $ignored)
    {
        $this->ignored = $ignored;
    }

    /**
     * @return string
     */
    public function getFactoryName(): string
    {
        return $this->factoryName;
    }

    /**
     * @param string $factoryName
     */
    public function setFactoryName(string $factoryName)
    {
        $this->factoryName = $factoryName;
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    public function canLog(Exception $exception): bool
    {
        if (in_array(get_class($exception), $this->exceptions) && !in_array(get_class($exception), $this->ignored)){
            return true;
        }

        return false;
    }



}