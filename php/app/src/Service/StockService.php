<?php
namespace App\Service;

use PDO;

class StockService
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Произвести резервирование товара sku определенного количества qty
     *
     * @param $sku
     * @param $qty
     * @return bool
     */
    public function reserveQty($sku, $qty) {
        $stmt = $this->pdo->prepare(
"UPDATE stocks
        SET reserved_qty = reserved_qty + :qty
        WHERE sku = :sku AND qty - reserved_qty - :qty >= 0");
        $stmt->execute([
            ':qty' => $qty,
            ':sku' => $sku
        ]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Произвести выдачу товара sku определенного количества qty
     *
     * @param $sku
     * @param $qty
     * @return bool
     */
    public function reserveReleaseQty($sku, $qty) {
        $stmt = $this->pdo->prepare(
    "UPDATE stocks 
            SET reserved_qty = reserved_qty - :qty, qty = qty - :qty
            WHERE sku = :sku AND reserved_qty - :qty >= 0 AND qty - :qty >= 0");
        $stmt->execute([
            ':sku' => $sku,
            ':qty' => $qty
        ]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Произвести пополнение товара sku определенного количества qty
     *
     * @param $sku
     * @param $qty
     * @return bool
     */
    public function increaseQty($sku, $qty) {
        $stmt = $this->pdo->prepare(
            "UPDATE stocks SET qty = qty + :qty WHERE sku = :sku");
        $stmt->execute([
            ':sku' => $sku,
            ':qty' => $qty
        ]);
        if ($stmt->rowCount() > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}