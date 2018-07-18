<?php

namespace Tests\Common\Parse;


use PHPUnit\Framework\TestCase;
use Tests\Utils\DatabaseErrorLogger;
use Tests\Utils\DatabaseErrorLoggerFactory;
use Tests\Utils\JsonErrorResponseFactory;
use Tests\Utils\SmsErrorLogger;
use Tests\Utils\SmsErrorLoggerFactory;
use Tests\Utils\TransferenciaEntreContasException;
use Zend\Mvc\ErrorLogger\Common\Parse\ConfigurationParser;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassDefinedAndIgnoredException;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\NoExceptionClassDefined;
use Zend\Mvc\ErrorLogger\Exceptions\Parse\NoLoggerDefinitionException;


class ConfigurationParserTest extends TestCase
{


    private function createConfiguration()
    {
        return [
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
                'loggers' => [
                    [
                        'logger' => DatabaseErrorLogger::class,
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ],
                    [
                        'logger' => SmsErrorLogger::class,
                        'factory' => SmsErrorLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    private function createConfigurationWithoutJsonErrorResponseFactory()
    {
        return [
            'error-logging' => [
                'loggers' => []
            ]
        ];
    }



    private function createCongigurationWithoutLoggers()
    {
        return [
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
                'loggers' => []
            ]
        ];
    }

    private function createConfigurationWithNoLoggerClass()
    {
        return [
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
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
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
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
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
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
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
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
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
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
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
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
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
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

    /**
     * @expectedException \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
     */
    public function testIfConfigurationHasJsonErrorResponseFactory()
    {
        // arrange
        $config = $this->createConfigurationWithoutJsonErrorResponseFactory();
        $parser = $this->createParser();

        // act
        $parser->parse($config);

        // assert
    }

}