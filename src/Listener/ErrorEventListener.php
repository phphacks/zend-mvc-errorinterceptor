<?php

namespace Zend\Mvc\ErrorInterceptor\Listener;


use Zend\Mvc\ErrorInterceptor\Common\Enums\Configuration;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;

class ErrorEventListener extends AbstractListenerAggregate
{

    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
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
        if (!configuration[Configuration::ERROR_LOGGING_ENABLE]){
            return;
        }

        // exceptions
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'handleExceptionError']);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleExceptionError'], 100);

        // php errors
        $this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'handlePhpError']);
    }

    public function handleExceptionError(MvcEvent $event)
    {
//        $exception = $event->getParam('exception');
//        if (!$exception){
//            return;
//        }
//
//        // os loggers são implementados na aplicação, mas devem extender da classe Logger do zend
//
//        $loggers = getLoggers($config);
//
//        foreach ( anda pelos loggers)
//        {
//            // se a exceção estiver na lista de ignoradas, não grava nada
//
//            // senão, grava o log da exceção
//
//            $logger = new Logger();
//            $logger->log(); // grava o log, analisar a diferença com o método info()
//
//            $logger->info(); //grava o log
//        }
//
//
//        return $event;
    }

    private function getLoggers($config)
    {
        // procura se o logger está no container

        // se não estiver cria

        // verifica se extende da classe Logger, se não for, dá exception ou avisa que não está gravando log... e retorna null, para o método handle entender que não é pra gravar log
    }

    public function handlePhpError(MvcEvent $event)
    {

        return $event;
    }

}