<?php

namespace Tests\Utils;

use Zend\Mvc\ErrorLogger\Custom\JsonErrorResponseFactoryInterface;
use Zend\View\Model\JsonModel;

class JsonErrorResponseFactory implements JsonErrorResponseFactoryInterface
{

    /**
     * @param string $errorMessage
     * @return JsonModel
     */
    public function createResponse(string $errorMessage): JsonModel
    {
        return new JsonModel([
            'Error' => true,
            'Message' => $errorMessage
        ]);
    }
}