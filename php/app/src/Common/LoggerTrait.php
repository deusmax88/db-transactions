<?php
namespace App\Common;

use App\Logger\Logger;

trait LoggerTrait
{
    protected Logger $logger;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function log($msg)
    {
        $this->logger->log($msg);
    }
}