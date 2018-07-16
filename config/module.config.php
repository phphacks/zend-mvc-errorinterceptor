<?php

return [
    'listeners' => [
        "Zend\Mvc\ErrorInterceptor\Listener\ErrorEventListener"
    ],
    'service_manager' => [
        'factories' => [
            "Zend\Mvc\ErrorInterceptor\Listener\ErrorEventListener" => Zend\Mvc\ErrorInterceptor\Factory\ErrorEventListenerFactory::class
        ]
    ]
];