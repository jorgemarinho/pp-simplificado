<?php

namespace App\Logging;
namespace App\Logging;

use Monolog\Logger;

class Log extends Logger
{
    public function __construct($name = 'pp_logger', array $handlers = [], array $processors = [])
    {
        parent::__construct($name, $handlers, $processors);
    }
}