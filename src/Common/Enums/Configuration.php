<?php

namespace Zend\Mvc\ErrorLogger\Common\Enums;

interface Configuration
{
    const ERROR_LOGGING_ENABLE = 'enable';
    const ERROR_LOGGING = 'error_logging';
    const LOGGERS = 'loggers';
    const LOGGER = 'logger';
    const TYPEOF = 'typeof';
    const IGNORED = 'ignored';
    const FACTORY = 'factory';
}