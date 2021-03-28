<?php
// Attention! This is first script attempt - leaving it
// here for historical purposes only
//
// This implementation is subject to consistency violation
// Because 2 concurrent scripts could bring could write update
// quantity so that it can became negative
// Would making our field non-negative solve our problem?
// Yes that's effective solves our problem

$pdo = new PDO("mysql:host=mysql;port=3306;dbname=stocks", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

try {
    $pdo->beginTransaction();
    echo "Begin transaction succeed\n";

    $sku = 1;
    $stmt = $pdo->prepare("SELECT qty FROM stocks WHERE sku = :sku");

    if($stmt->execute([':sku' => $sku])) {
        echo "SELECT successfully executed\n";
    }
    else {
        echo "SELECT went wrong\n";
    }

    $result = $stmt->fetchAll();
    $currentQty = $result[0]['qty'];

    echo "Current sku {$sku} qty {$currentQty}\n";
    echo "Trying to reserve our 1\n";

    // here we use atomic update using previously
    // selected result;
    $stmt = $pdo->prepare("UPDATE
        stocks 
        SET qty = qty-1, reserved_qty = reserved_qty + 1 
        WHERE sku = :sku AND qty = :qty");
    if($stmt->execute([':sku' => $sku, ":qty" => $currentQty])) {
        echo "UPDATE successfully executed\n";
        echo "Num of affected rows: {$stmt->rowCount()}\n";
    }
    else {
        echo "UPDATE went wrong\n";
    }

    echo "Performing transaction commit\n";
    if ($pdo->commit()) {
        echo "COMMIT successfully executed\n";
    }
    else {
        echo "COMMIT went wrong\n";
    }

    echo "Done\n";
}
catch(PDOException $e) {
    echo "Exception thrown during execution:\n ";
    echo $e->getMessage();
}
