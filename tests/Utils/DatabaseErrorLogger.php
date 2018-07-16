<?php

namespace Tests\Utils;

use Zend\Log\Logger;
use Zend\Log\Writer;

class DatabaseErrorLogger extends Logger
{
    public function __construct(FakeAdapter $fakeAdapter)
    {
        parent::__construct(null);

        $writer = new Writer\Mock();

        $this->addWriter($writer);
    }
}