<?php

namespace Zend\Mvc\ErrorInterceptor\Common\Parse;

use Zend\Mvc\ErrorInterceptor\Common\Enums\Configuration;
use Zend\Mvc\ErrorInterceptor\Common\ErrorLogging;

use Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassDefinedAndIgnoredException;
use Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException;
use Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined;
use Zend\Mvc\ErrorInterceptor\Exceptions\NoLoggerDefinitionException;
use Zend\Mvc\ErrorInterceptor\Common\LoggerDefinition;

class ConfigurationParser
{
    /**
     * @param $configuration
     * @return null|ErrorLogging
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws LoggerClassException
     * @throws NoLoggerDefinitionException
     * @throws NoExceptionClassDefined
     */
    public function parse($configuration)
    {
        if (isset($configuration[Configuration::ERROR_LOGGING])) {

            $errorLogging = new ErrorLogging();
            $errorLogging->setResponse($this->parseResponse($configuration));

            $this->hasLoggers($configuration[Configuration::ERROR_LOGGING]);

            foreach ($configuration[Configuration::ERROR_LOGGING][Configuration::LOGGERS] as $logger) {

                $loggerDefinition = new LoggerDefinition();

                $loggerDefinition->setClassName($this->hasLoggerClass($logger));
                $loggerDefinition->setExceptions($this->parseExceptionsList($logger, Configuration::TYPEOF));
                $loggerDefinition->setIgnored($this->parseIgnoredList($logger, Configuration::IGNORED));
                $loggerDefinition->setParameters($logger[Configuration::CONFIG]);

                $this->verifyExceptionClassAndIgnoredList($logger);

                $errorLogging->addLogger($loggerDefinition);
            }

            return $errorLogging;
        }
        return null;
    }


    /**
     * @param array $loggerConfiguration
     * @throws LoggerClassDefinedAndIgnoredException
     */
    private function verifyExceptionClassAndIgnoredList(array $loggerConfiguration)
    {
        foreach ($loggerConfiguration[Configuration::TYPEOF] as $exceptionClass) {
            if (in_array($exceptionClass, $loggerConfiguration[Configuration::IGNORED])){
                throw new LoggerClassDefinedAndIgnoredException(sprintf('Exception class %s is defined for logging and ignore',
                    $exceptionClass));
            }
        }
    }

    /**
     * @param array $loggerConfiguration
     * @return string
     * @throws LoggerClassException
     */
    private function hasLoggerClass(array $loggerConfiguration): string
    {
        if (!isset($loggerConfiguration[Configuration::LOGGER])
        || $loggerConfiguration[Configuration::LOGGER] == null
        || $loggerConfiguration[Configuration::LOGGER] == ''){
            throw new LoggerClassException('There is no Class type for Logger %s');
        }

        return $loggerConfiguration[Configuration::LOGGER];
    }

    /**
     * @param array $configuration
     * @throws NoLoggerDefinitionException
     */
    private function hasLoggers(array $configuration)
    {
        if (!isset($configuration[Configuration::LOGGERS])
        || Count($configuration[Configuration::LOGGERS]) == 0) {
            throw new NoLoggerDefinitionException('There is no Logger definitions in configuration');
        }
    }

    public function parseResponse(array $loggingConfiguration): string
    {
        if (is_null($loggingConfiguration[Configuration::ERROR_LOGGING][Configuration::RESPONSE])) {
            return '';
        } else {
            return $loggingConfiguration[Configuration::ERROR_LOGGING][Configuration::RESPONSE];
        }
    }

    /**
     * @param array $loggerConfiguration
     * @param string $exceptionListIdentifier
     * @return array
     * @throws NoExceptionClassDefined
     */
    public function parseExceptionsList(array $loggerConfiguration, string $exceptionListIdentifier): array
    {
        $array = $this->getExceptionList($loggerConfiguration, $exceptionListIdentifier);

        if (Count($array) == 0) {
            throw new NoExceptionClassDefined(sprintf('No one Exception Class defined for logger %s',
                $loggerConfiguration[Configuration::LOGGER]));
        }

        return $array;
    }

    public function parseIgnoredList(array $loggerConfiguration, string $exceptionListIdentifier): array
    {
        return $this->getExceptionList($loggerConfiguration, $exceptionListIdentifier);
    }

    private function getExceptionList(array $loggerConfiguration, string $exceptionListIdentifier): array
    {
        if (isset($loggerConfiguration[$exceptionListIdentifier])) {
            $array = [];

            foreach ($loggerConfiguration[$exceptionListIdentifier] as $item) {
                $array[] = $item;
            }

            return $array;
        }

        return [];
    }
}