<?php

namespace Tests\Utils;

use Zend\Log\Logger;

class DatabaseErrorLogger extends Logger
{
    public function __construct(FakeAdapter $fakeAdapter)
    {
        parent::__construct(null);
    }
}