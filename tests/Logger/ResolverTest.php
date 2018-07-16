<?php

namespace Tests\Logger;

use Tests\Utils\DatabaseErrorLoggerFactory;
use Tests\Utils\SmsErrorLoggerFactory;
use Tests\Utils\WrongErrorLogger;
use Tests\Utils\WrongJsonErrorResponseFactory;
use Tests\Utils\WrongLoggerFactory;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Utils\DatabaseErrorLogger;
use Tests\Utils\JsonErrorResponseFactory;
use Tests\Utils\SmsErrorLogger;
use Tests\Utils\TransferenciaEntreContasException;
use Zend\EventManager\EventManager;
use Zend\Mvc\Application;
use Zend\Mvc\ErrorInterceptor\Logger\Resolver;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ResolverTest extends TestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var MvcEvent
     */
    protected $mvcEvent;

    /**
     * @var ServiceManager
     */
    protected $container;

    /**
     * @throws \ReflectionException
     */
    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $request    = new HttpRequest();
        $response   = new HttpResponse();
        $this->container  = new ServiceManager();

        (new Config(['services' => [
            'EventManager' => new EventManager(),
            'Request' => $request,
            'Response' => $response
        ]]))->configureServiceManager($this->container);

        $this->application = $this->applicationFactory($this->container);

        $this->mvcEvent = new MvcEvent();

        $this->mvcEvent->setApplication($this->application);
    }

    /**
     * @param ServiceManager $container
     * @return Application
     * @throws \ReflectionException
     */
    private function applicationFactory(ServiceManager $container)
    {
        $r = new ReflectionMethod(Application::class, '__construct');
        $arguments = $r->getParameters();
        $first = array_shift($arguments);
        if ($first->getName() !== 'serviceManager') {
            // V2 construction
            return new Application([], $container);
        }
        return new Application($container);
    }

    private function createConfigurationWithTwoLoggers()
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

    private function createConfigurationWithSomeLoggersHavingFactory()
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
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    private function createConfigurationWithWrongLogger()
    {
        return [
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
                'loggers' => [
                    [
                        'logger' => WrongErrorLogger::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    private function createConfigurationWithWrongFactory()
    {
        return [
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
                'loggers' => [
                    [
                        'logger' => SmsErrorLogger::class,
                        'factory' => WrongLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    /**
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfLoggerFactoryDontImplementsFactoryInterface()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithWrongFactory());

        // act
        $resolver->resolveConfiguration($this->mvcEvent);

        // assert
    }

    private function createConfigurationWithWrongJsonErrorResponseFactory()
    {
        return [
            'error-logging' => [
                'response' => WrongJsonErrorResponseFactory::class,
                'loggers' => [
                    [
                        'logger' => DatabaseErrorLogger::class,
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [TransferenciaEntreContasException::class],
                        'ignored' => []
                    ]
                ]
            ]
        ];
    }

    /**
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfJsonErrorResponseFactoryImplementsJsonErrorResponseFactoryInterface()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithWrongJsonErrorResponseFactory());

        // act
        $resolver->resolveConfiguration($this->mvcEvent);

        // assert
    }

    /**
     * @expectedException \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfLoggerDontExtendsZendLoggerWasInformed()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithWrongLogger());

        // act
        $resolver->resolveConfiguration($this->mvcEvent);

        // assert
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfGetLoggersResolveTwoLoggers()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithTwoLoggers());

        // act
        $errorLogging = $resolver->resolveConfiguration($this->mvcEvent);

        // assert
        $this->assertCount(2, $errorLogging->getLoggers());
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfGetLoggersPutLoggersInServiceManager()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithSomeLoggersHavingFactory());

        // act
        $errorLogging = $resolver->resolveConfiguration($this->mvcEvent);

        // assert
        foreach ($errorLogging->getLoggers() as $logger) {
            self::assertTrue($this->container->has($logger->getClassName()));
        }

    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidFactoryException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Logger\InvalidLoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorInterceptor\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function testIfResolveConfigurationPutJsonErrorResponseFactoryInServiceManager()
    {
        // arrange
        $resolver = new Resolver($this->createConfigurationWithSomeLoggersHavingFactory());

        // act
        $errorLogging = $resolver->resolveConfiguration($this->mvcEvent);

        // assert
        self::assertTrue($this->container->has($errorLogging->getResponse()));

    }
}