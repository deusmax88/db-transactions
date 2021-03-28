<?php
namespace App\Logger;

class NullLogger implements Logger
{
    public function __construct()
    {
    }

    public function log($msg)
    {
        // Sorry, but no functionality provided
    }
}