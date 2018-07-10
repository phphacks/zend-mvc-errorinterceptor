<?php

namespace Zend\Mvc\ErrorInterceptor\Common;

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
     * @var string[]
     */
    protected $parameters = [];

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
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string[] $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }


}