<?php

namespace Zend\Mvc\ErrorLogger\Logger;


use Zend\Log\Logger;
use Zend\Mvc\Di\Dependency\Injection\InjectableFactory;
use Zend\Mvc\ErrorLogger\Common\ErrorLogging;
use Zend\Mvc\ErrorLogger\Common\LoggerDefinition;
use Zend\Mvc\ErrorLogger\Common\Parse\ConfigurationParser;
use Zend\Mvc\ErrorLogger\Custom\JsonErrorResponseFactoryInterface;
use Zend\Mvc\ErrorLogger\Exceptions\Logger\InvalidFactoryException;
use Zend\Mvc\ErrorLogger\Exceptions\Logger\InvalidJsonErrorResponseFactoryClassException;
use Zend\Mvc\ErrorLogger\Exceptions\Logger\InvalidLoggerClassException;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

class Resolver
{

    private $configuration;
    /**
     * @var ConfigurationParser
     */
    private $configurationParser;

    /**
     * Resolver constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $this->configurationParser = new ConfigurationParser();
    }

    /**
     * @param MvcEvent $event
     * @return ErrorLogging
     * @throws InvalidFactoryException
     * @throws InvalidJsonErrorResponseFactoryClassException
     * @throws InvalidLoggerClassException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassDefinedAndIgnoredException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\LoggerClassException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoExceptionClassDefined
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoJsonErrorResponseFactoryClassDefinedException
     * @throws \Zend\Mvc\ErrorLogger\Exceptions\Parse\NoLoggerDefinitionException
     */
    public function resolveConfiguration(MvcEvent $event): ErrorLogging
    {
        $errorLogging = $this->configurationParser->parse($this->configuration);

        $this->validateResponseFactory($errorLogging);

        $serviceManager = $this->getServiceManager($event);

        $injector = new InjectableFactory();
        $injector->__invoke($serviceManager, $errorLogging->getResponse());

        foreach ($errorLogging->getLoggers() as $logger) {
            $this->validateLoggerClassType($logger);

            if ($logger->getFactoryName() != ''){
                $this->validateFactoryClassType($logger);

                $class = $logger->getFactoryName();
                /**
                 * @var FactoryInterface $factory
                 */
                $factory = new $class();
                $loggerInstance = $factory->__invoke($serviceManager, $logger->getClassName());
                $serviceManager->setService($logger->getClassName(), $loggerInstance);
            } else {
                $injector->__invoke($serviceManager, $logger->getClassName());
            }
        }

        return $errorLogging;
    }

    private function getServiceManager(MvcEvent $event): ServiceLocatorInterface
    {
        $application = $event->getApplication();
        return $application->getServiceManager();
    }

    /**
     * @param ErrorLogging $errorLogging
     * @throws InvalidJsonErrorResponseFactoryClassException
     */
    private function validateResponseFactory(ErrorLogging $errorLogging): void
    {
        if (!in_array(JsonErrorResponseFactoryInterface::class, class_implements($errorLogging->getResponse()))){
            throw new InvalidJsonErrorResponseFactoryClassException(sprintf('Json Error Response Factory "%s" must implements "Zend\Mvc\ErrorInterceptor\Custom\JsonErrorResponseFactoryInterface"',
                $errorLogging->getResponse()));
        }
    }

    /**
     * @param LoggerDefinition $loggerDefinition
     * @throws InvalidFactoryException
     */
    private function validateFactoryClassType(LoggerDefinition $loggerDefinition): void
    {
        if (!in_array(FactoryInterface::class, class_implements($loggerDefinition->getFactoryName()))){
            throw new InvalidFactoryException(sprintf('Factory "%s" must implements "Zend\ServiceManager\Factory\FactoryInterface"',
                $loggerDefinition->getFactoryName()));
        }
    }
    /**
     * @param LoggerDefinition $loggerDefinition
     * @throws InvalidLoggerClassException
     */
    private function validateLoggerClassType(LoggerDefinition $loggerDefinition): void
    {
        if (!is_subclass_of($loggerDefinition->getClassName(), Logger::class)){
            throw new InvalidLoggerClassException(sprintf('Class "%s" is not instance of "Zend\Log\Logger"',
                $loggerDefinition->getClassName()));
        }
    }

}