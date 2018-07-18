<?php

return [
    'listeners' => [
        "Zend\Mvc\ErrorLogger\Listener\ErrorEventListener"
    ],
    'service_manager' => [
        'factories' => [
            "Zend\Mvc\ErrorLogger\Listener\ErrorEventListener" => Zend\Mvc\ErrorLogger\Factory\ErrorEventListenerFactory::class
        ]
    ]
];