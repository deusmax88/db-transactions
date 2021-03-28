<?php
namespace App\Command;

use App\Common\LoggerTrait;
use App\Service\StockService;

class IncreaseCommand
{
    use LoggerTrait;

    /**
     * @var StockService
     */
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function increase($sku, $qty)
    {
        if ($result = $this->stockService->increaseQty($sku, $qty)) {
            $msg = "INCREASE COMMAND: SKU: $sku QTY: $qty INCREASED\n";
        }
        else {
            $msg = "INCREASE COMMAND: SKU: $sku QTY: $qty FAILED\n";
        }

        $this->logger->log($msg);

        return $result;
    }
}