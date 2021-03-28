<?php

use App\Command\ReservationCommand;

require_once(__DIR__."/../common2.php");

$sku = $argv[1];
$qty = $argv[2];
$delay = $argv[3];

if ($delay) {
    sleep($delay);
}

$reservationCommand = $container->get(ReservationCommand::class);

exit($reservationCommand->reserve($sku, $qty) ? 0 : 1);