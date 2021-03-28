<?php
// In this script i want
// test concurrent execution of
// multiple reservations, releases and increment
// commands against stocks
// By the end i want to see table
// in consistent state, i.e.
// num of reservations not greater than qty
// so let's start


$reservations = [
    2,
    2,
    4,
    3,
    5,
];

$releases = [
    1,
    2,
    4,
    3,
    5
];

$increments = [
    3,
    4,
    2,
    1,
    4
];

$procs = [
    'reservations' => [],
    'releases' => [],
    'increments' => []
];

$xdebugMode = ini_get('xdebug.mode');
$xdebugClientPort = ini_get('xdebug.client_port');
$xdebugClientHost = ini_get('xdebug.client_host');
$phpInterperterDefinitionStr = "-dxdebug.mode=$xdebugMode -dxdebug.client_port=$xdebugClientPort ".
    " -dxdebug.client_host=$xdebugClientHost";

// In here sku is permanent and equals 1
$sku = 1;
for ($i = 0; $i < 100; $i++) {
    $qty = mt_rand(1, 10);
    $rndDelay = mt_rand(10, 15);
    echo "MAIN: [$i] Reservation attempt SKU: $sku QTY: $qty DELAY: $rndDelay".PHP_EOL;
    // proc_open is non-blocking command
    // Here i'am gonna try to proxy xdebug-config
    $proc = proc_open("php ".$phpInterperterDefinitionStr." ".__DIR__."/reservation-reserve.php 1 $qty $rndDelay", [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w']
    ],
    $pipes);

    $procs['reservations'][] = [
        'proc' => $proc,
        'pipes' => $pipes,
    ];

    echo "MAIN: [$i] Reservation attempt finished SKU: $sku QTY: $qty DELAY: $rndDelay".PHP_EOL;
}

for($i = 0; $i < 100; $i++) {
    $qty = mt_rand(1, 10);
    $rndDelay = mt_rand(10, 15);
    echo "MAIN: [$i] Release command with SKU: $sku QTY: $qty DELAY: $rndDelay".PHP_EOL;
    // proc_open is non-blocking command
    $proc = proc_open("php ".__DIR__."/reservation-release.php 1 $qty $rndDelay", [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w']
    ],
        $pipes);

    $procs['releases'][] = [
        'proc' => $proc,
        'pipes' => $pipes,
    ];

    echo "MAIN: [$i] Release attempt finished SKU: $sku QTY: $qty DELAY: $rndDelay".PHP_EOL;
}


for($i = 0; $i < 100; $i++) {
    $qty = mt_rand(1, 10);
    $rndDelay = mt_rand(10, 15);
    echo "MAIN: [$i] Increase attempt SKU: $sku QTY: $qty".PHP_EOL;
    // proc_open is non-blocking command
    $proc = proc_open("php ".__DIR__."/reservation-increase.php 1 $qty $rndDelay", [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w']
    ],
        $pipes);

    $procs['increments'][] = [
        'proc' => $proc,
        'pipes' => $pipes,
    ];

    echo "MAIN: [$i] Increase attempt finished SKU: $sku QTY: $qty DELAY: $rndDelay".PHP_EOL;
}

// Let's wait for all processes to finish their jobs
foreach($procs['reservations'] as $idx => $proc) {
    $output = stream_get_contents($proc['pipes'][1]);
    $code = proc_close($proc['proc']);
    echo "MAIN: Reservation [$idx] proc exit code: ".$code.PHP_EOL;
    echo "MAIN: Reservation [$idx] proc output:".PHP_EOL;
    echo $output;
    echo PHP_EOL;
}

foreach($procs['releases'] as $idx => $proc) {
    $output = stream_get_contents($proc['pipes'][1]);
    $code = proc_close($proc['proc']);
    echo "MAIN: Release [$idx] proc exit code: ".$code.PHP_EOL;
    echo "MAIN: Release [$idx] proc output:".PHP_EOL;
    echo $output;
    echo PHP_EOL;
}
foreach($procs['increments'] as $idx => $proc) {
    $output = stream_get_contents($proc['pipes'][1]);
    $code = proc_close($proc['proc']);
    echo "MAIN: Increment [$idx] proc exit code: ".$code.PHP_EOL;
    echo "MAIN: Increment [$idx] proc output:".PHP_EOL;
    echo $output;
    echo PHP_EOL;
}