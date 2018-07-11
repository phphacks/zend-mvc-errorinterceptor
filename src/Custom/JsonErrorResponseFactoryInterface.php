<?php

namespace Zend\Mvc\ErrorInterceptor\Custom;

use Zend\View\Model\JsonModel;

interface JsonErrorResponseFactoryInterface
{
    public function createResponse(string $errorMessage): JsonModel;
}