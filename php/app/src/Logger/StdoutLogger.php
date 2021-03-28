<?php
namespace App\Logger;

class StdoutLogger implements Logger
{
    protected $stream;

    public function __construct()
    {
        $this->stream = STDOUT;
    }

    public function log($msg)
    {
        return fwrite($this->stream, $msg);
    }
}