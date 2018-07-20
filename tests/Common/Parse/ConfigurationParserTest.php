<?php

namespace Tests\Common\Parse;


use PHPUnit\Framework\TestCase;
use Tests\Utils\DatabaseErrorLogger;
use Tests\Utils\DatabaseErrorLoggerFactory;
use Tests\Utils\TransferenciaEntreContasException;
use Zend\Mvc\ErrorLogger\Common\Parse\ConfigurationParser;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassDefinedAndIgnoredException;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\NoExceptionClassDefined;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\NoLoggerDefinitionException;


class ConfigurationParserTest extends TestCase
{
    private function createCongigurationWithoutLoggers()
    {
        return [
            'error_logging' => [
                'loggers' => []
            ]
        ];
    }

    private function createConfigurationWithNoLoggerClass()
    {
        return [
            'error_logging' => [
                'loggers' => [
                    [
                        'logger' => '',
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    private function createParser()
    {
        return new ConfigurationParser();
    }

    private function createConfigurationWithExceptionClassOnIgnoredList()
    {
        return [
            'error_logging' => [
                'loggers' => [
                    [
                        'logger' => DatabaseErrorLogger::class,
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => [TransferenciaEntreContasException::class]

                    ]
                ]
            ]
        ];
    }


    private function createConfigurationWithNoExceptionClassOnLogger()
    {
        return [
            'error_logging' => [
                'loggers' => [
                    [
                        'logger' => DatabaseErrorLogger::class,
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }


    /**
     * @expectedException \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoExceptionClassDefined
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     */
    public function testIfNoExceptionClassDefinedForLogger()
    {
        // arrange
        $config = $this->createConfigurationWithNoExceptionClassOnLogger();
        $parser = $this->createParser();

        // act
        $parser->parse($config);

        // assert
    }

    /**
     * @expectedException \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     */
    public function testIfExceptionClassExistsOnIgnoredList()
    {
        // arrange
        $config = $this->createConfigurationWithExceptionClassOnIgnoredList();
        $parser = $this->createParser();

        // act
        $parser->parse($config);

        // assert
    }

    /**
     * @expectedException \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoLoggerDefinitionException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     */
    public function testIfConfigurationHasNoLoggers()
    {
        // arrange
        $config = $this->createCongigurationWithoutLoggers();
        $parser = $this->createParser();
        // act
        $parser->parse($config);

        // assert
    }

    /**
     * @expectedException \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     */
    public function testIfLoggerConfigurationHasNoLoggerClass()
    {
        // arrange
        $config = $this->createConfigurationWithNoLoggerClass();
        $parser = $this->createParser();

        // act
        $parser->parse($config);

        // assert
    }

}