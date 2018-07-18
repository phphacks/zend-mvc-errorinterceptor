<?php

namespace Zend\Mvc\ErrorLogger\Custom;

use Zend\View\Model\JsonModel;

interface JsonErrorResponseFactoryInterface
{
    public function createResponse(string $errorMessage): JsonModel;
}