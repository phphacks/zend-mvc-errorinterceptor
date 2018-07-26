<?php

namespace Zend\Mvc\ErrorLogger\Common;

use Exception;
use TypeError;

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
     * @param Exception|TypeError $exception
     * @return bool
     */
    public function canLog($exception): bool
    {
        if ((in_array(get_class($exception), $this->exceptions) ||
                $this->checkInstanceInList($exception, $this->exceptions)) &&
            !in_array(get_class($exception), $this->ignored) &&
            !$this->checkInstanceInList($exception, $this->ignored)){
            return true;
        }

        return false;
    }

    private function checkInstanceInList($exceptionToCheck, array $exceptions): bool
    {
        foreach ($exceptions as $exception) {
            if ($exceptionToCheck instanceof $exception ){
                return true;
            }
        }

        return false;
    }



}