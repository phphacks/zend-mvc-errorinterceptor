<?php

namespace Tests\Utils;


use Zend\Log\Logger;

class SmsErrorLogger extends Logger
{
    public function __construct()
    {
        parent::__construct(null);
    }
}