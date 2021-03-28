<?php

use App\Command\IncreaseCommand;

require_once(__DIR__."/../common2.php");

$sku = $argv[1];
$qty = $argv[2];
$delay = $argv[3];

if ($delay) {
    sleep($delay);
}

$increaseCommand = $container->get(IncreaseCommand::class);

exit($increaseCommand->increase($sku, $qty) ? 0 : 1);