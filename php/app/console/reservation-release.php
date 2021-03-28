<?php

use App\Command\ReservationReleaseCommand;

require_once(__DIR__."/../common2.php");

$sku = $argv[1];
$qty = $argv[2];
$delay = $argv[3];

if ($delay) {
    sleep($delay);
}

$reservationReleaseCommand = $container->get(ReservationReleaseCommand::class);

exit($reservationReleaseCommand->reservationRelease($sku, $qty) ? 0 : 1);