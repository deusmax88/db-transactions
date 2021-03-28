<?php
namespace App\Command;

use App\Common\LoggerTrait;
use App\Service\StockService;

class ReservationCommand
{
    use LoggerTrait;

    /**
     * @var StockService StockService
     */
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function reserve($sku, $qty)
    {
        if ($result = $this->stockService->reserveQty($sku, $qty)) {
            $msg = "RESERVATION COMMAND: SKU: $sku QTY: $qty RESERVED\n";
        }
        else{
            $msg = "RESERVATION COMMAND: SKU: $sku QTY: $qty FAILED\n";
        }

        $this->logger->log($msg);

        return $result;
    }
}