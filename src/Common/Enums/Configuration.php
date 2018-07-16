<?php

namespace Zend\Mvc\ErrorInterceptor\Common\Enums;

interface Configuration
{
    const ERROR_LOGGING_ENABLE = 'enable';
    const ERROR_LOGGING = 'error-logging';
    const RESPONSE = 'response';
    const LOGGERS = 'loggers';
    const LOGGER = 'logger';
    const TYPEOF = 'typeof';
    const IGNORED = 'ignored';
    const FACTORY = 'factory';
}