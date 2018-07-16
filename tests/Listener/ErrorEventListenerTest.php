<?php

namespace Tests\Listener;

use PHPUnit\Framework\TestCase;
use Tests\Utils\DatabaseErrorLogger;
use Tests\Utils\DatabaseErrorLoggerFactory;
use Tests\Utils\JsonErrorResponseFactory;
use Tests\Utils\SmsErrorLogger;
use Tests\Utils\SmsErrorLoggerFactory;
use Tests\Utils\TransferenciaEntreContasException;
use Zend\Log\Logger;
use Zend\Mvc\Application;
use Zend\Mvc\Exception\RuntimeException;
use Zend\Mvc\MvcEvent;

class ErrorEventListenerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testWhenAnRequestWithErrorIsMade()
    {
        /** @var Logger*/
        $logger = null;

        $config = [
            'modules' => [
                'Zend\Mvc\ErrorInterceptor',
                'Zend\Router',
            ],
            'module_listener_options' => [],
            'error-logging' => [
                'response' => JsonErrorResponseFactory::class,
                'loggers' => [
                    [
                        'logger' => DatabaseErrorLogger::class,
                        'factory' => DatabaseErrorLoggerFactory::class,
                        'typeof' => [
                            TransferenciaEntreContasException::class,
                            \RuntimeException::class,
                            RuntimeException::class,
                            \Zend\View\Exception\RuntimeException::class
                        ],
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

        $application = Application::init($config);

        $mvcEvent = new MvcEvent();
        $mvcEvent->setApplication($application);
        try {
            $application->run();

        } catch (\Exception $ex) { }

        $logger = $application->getServiceManager()->get(DatabaseErrorLogger::class);
        $this->assertNotNull($logger);

        $writers = $logger->getWriters();
        $writer = $writers->extract();

        $this->assertTrue(isset($writer->events[0]));
        $conteudo = $writer->events[0];

        $this->assertNotNull($conteudo);

    }
}