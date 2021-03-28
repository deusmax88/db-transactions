<?php
require_once("./vendor/autoload.php");

use App\Command\IncreaseCommand;
use App\Command\ReservationCommand;
use App\Command\ReservationReleaseCommand;
use App\Logger\StdoutLogger;
use App\Service\StockService;

$pdo = new PDO("mysql:host=mysql;port=3306;dbname=stocks", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stdoutLogger = new StdoutLogger();
$stockService = new StockService($pdo);
$reservationCommand = new ReservationCommand($stockService);
    $reservationCommand->setLogger($stdoutLogger);
$reservationReleaseCommand = new ReservationReleaseCommand($stockService);
    $reservationReleaseCommand->setLogger($stdoutLogger);
$increaseCommand = new IncreaseCommand($stockService);
    $increaseCommand->setLogger($stdoutLogger);
