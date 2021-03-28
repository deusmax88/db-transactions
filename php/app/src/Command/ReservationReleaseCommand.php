<?php
namespace App\Command;

use App\Common\LoggerTrait;
use App\Service\StockService;

class ReservationReleaseCommand
{
    use LoggerTrait;

    /**
     * @var StockService
     */
    protected StockService $stockService;

    /**
     * ReservationReleaseCommand constructor.
     * @param StockService $stockService
     */
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function reservationRelease($sku, $qty)
    {

        if ($result = $this->stockService->reserveReleaseQty($sku, $qty)) {
            $msg = "RESERVATION RELEASE COMMAND: SKU: $sku QTY: $qty RELEASED\n";
        }
        else{
            $msg = "RESERVATION RELEASE COMMAND: SKU: $sku QTY: $qty FAILED\n";
        }

        $this->logger->log($msg);

        return $result;
    }
}