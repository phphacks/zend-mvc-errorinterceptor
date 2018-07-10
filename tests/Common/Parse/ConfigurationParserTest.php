<?php

namespace Tests\Common\Parse;


use PHPUnit\Framework\TestCase;
use Zend\Mvc\ErrorInterceptor\Common\Parse\ConfigurationParser;
use Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassDefinedAndIgnoredException;
use Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined;
use Zend\Mvc\ErrorInterceptor\Exceptions\NoLoggerDefinitionException;


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
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => [],
                        'config' => ['Adapter' => 'LogAdapterDeTeste']
                    ],
                    [
                        'logger' => SmsErrorLogger::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => [],
                        'config' => ['subjects' => [997429237, 000000, '...']]
                    ]
                ]
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
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => [],
                        'config' => ['Adapter' => 'LogAdapterDaPoha']
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
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => [TransferenciaEntreContasException::class],
                        'config' => ['Adapter' => 'LogAdapterDeTeste']
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
                        'typeof' => [],
                        'ignored' => [],
                        'config' => ['Adapter' => 'LogAdapterDeTeste']
                    ]
                ]
            ]
        ];
    }


    /**
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined
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
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassDefinedAndIgnoredException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined
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
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\NoLoggerDefinitionException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoExceptionClassDefined
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException
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
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException
     * @throws LoggerClassDefinedAndIgnoredException
     * @throws NoLoggerDefinitionException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\NoExceptionClassDefined
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


    public function testParseConfiguration()
    {
        // arrange
        $config = $this->createConfiguration();
        $parser = $this->createParser();

        // act
        $parser->parse($config);

        // assert
        $this->assertTrue(true);
    }
}