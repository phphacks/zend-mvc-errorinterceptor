<?php

namespace Tests\Listener;

use PHPUnit\Framework\TestCase;
use Zend\Config\Config;
use Zend\EventManager\EventManager;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class ErrorEventListenerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testWhenAnRequestWithErrorIsMade()
    {
        $config = [
            'services' => [
                'EventManager' => new EventManager(),
                'Request' => new Request(),
                'Response' => new Response()
            ]
        ];

        $serviceManager = new ServiceManager($config);
        $application = Application::init($config);
    }
}