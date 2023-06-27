<?php

namespace App\Point\Calculator;

use App\Point\PointEnums;
use Exception;

class PointCalculatorFactory
{
    /**
     * @throws Exception
     */
    public static function create($type): PointCalculator
    {
        return match ($type) {
            PointEnums::SOURCE_CONSUMPTION_INVOICE, PointEnums::SOURCE_CONSUMPTION_INPUT => new ConsumptionPointCalculator(),
            PointEnums::SOURCE_SCAN_STORE => new ScanStorePointCalculator(),
            default => throw new Exception("Invalid calculator type"),
        };
    }
}
