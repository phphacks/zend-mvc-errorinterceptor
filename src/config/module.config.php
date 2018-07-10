<?php

return [
    'listeners' => [
        Zend\Mvc\ErrorInterceptor\Listener\ErrorEventListener::class
    ],
    'service_manager' => [
        'factories' => [
            Zend\Mvc\ErrorInterceptor\Listener\ErrorEventListener::class => Zend\Mvc\ErrorInterceptor\Factory\ErrorEventListenerFactory::class
        ]
    ]
];