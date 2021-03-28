<?php
require_once("vendor/autoload.php");

use App\Command\IncreaseCommand;
use App\Command\ReservationCommand;
use App\Command\ReservationReleaseCommand;
use App\Logger\Logger;
use App\Logger\StdoutLogger;
use Psr\Container\ContainerInterface;

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    'database.port' => 3306,
    'database.host' => 'mysql',
    'database.user' => 'root',
    'database.password' => '',
    'database.database' => 'stocks',

    PDO::class => function (ContainerInterface $container) {
        $host = $container->get('database.host');
        $port = $container->get('database.port');
        $dbname = $container->get('database.database');
        $user = $container->get('database.user');
        $password = $container->get('database.password');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

    },

    Logger::class => DI\autowire(StdoutLogger::class),
    ReservationCommand::class => DI\autowire()
        ->method('setLogger', DI\get(Logger::class)),
    IncreaseCommand::class => DI\autowire()
        ->method('setLogger', DI\get(Logger::class)),
    ReservationReleaseCommand::class => DI\autowire()
        ->method('setLogger', DI\get(Logger::class))
]);

$container = $containerBuilder->build();