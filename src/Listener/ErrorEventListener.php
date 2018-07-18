<?php

namespace Zend\Mvc\ErrorLogger\Listener;


use Exception;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\ErrorLogger\Custom\JsonErrorResponseFactoryInterface;
use Zend\Mvc\ErrorLogger\Logger\Resolver;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use \Zend\ServiceManager\ServiceLocatorInterface;

class ErrorEventListener extends AbstractListenerAggregate
{

    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(array $configuration)
    {
        $this->resolver = new Resolver($configuration);
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        // exceptions
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'handleExceptionError']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleExceptionError'], 100);

        // php errors
        //$this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'handlePhpError']);
    }

    /**
     * @param MvcEvent $event
     * @return MvcEvent
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function handleExceptionError(MvcEvent $event)
    {
        return $this->handleError($event);
    }

    /**
     * @param MvcEvent $event
     * @return MvcEvent
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    private function handleError(MvcEvent $event): MvcEvent
    {
        if ($event->isError()) {

            /** @var Exception $exception */
            $exception = $event->getParam('exception');
            if (!$exception) {
                return $event;
            }
            $serviceManager = $this->getServiceManagerFromMvcEvent($event);

            $errorLogging = $this->resolver->resolveConfiguration($event);

            foreach ($errorLogging->getLoggers() as $logger) {
                if ($logger->canLog($exception)) {
                    /** @var Logger $loggerInstance */
                    $loggerInstance = $serviceManager->get($logger->getClassName());
                    $loggerInstance->info($exception->getMessage());
                }
            }

            /** @var JsonErrorResponseFactoryInterface $jsonErrorResponseFactory */
            $jsonErrorResponseFactory = $serviceManager->get($errorLogging->getResponse());

            $event->setViewModel($jsonErrorResponseFactory->createResponse($exception->getMessage()));
        }
        return $event;
    }

    /**
     * @param MvcEvent $event
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    private function getServiceManagerFromMvcEvent(MvcEvent $event): ServiceLocatorInterface
    {
        $application = $event->getApplication();
        return $application->getServiceManager();
    }

    /**
     * @param MvcEvent $event
     * @return MvcEvent
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     * @throws \Zend\Mvc\Di\Exceptions\UnsolvableDependencyException
     */
    public function handlePhpError(MvcEvent $event)
    {
        return $this->handleError($event);
    }

}