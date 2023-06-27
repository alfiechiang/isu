<?php

namespace App\Point\Calculator;

use InvalidArgumentException;

class ConsumptionPointCalculator implements PointCalculator
{
    public function calculate($amount = null): float
    {
        if ($amount == null || $amount < 0) {
            throw new InvalidArgumentException("錯誤轉換消費金額.");
        }
        return floor($amount / 50);
    }
}
