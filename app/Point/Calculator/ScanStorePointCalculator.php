<?php

namespace App\Point\Calculator;

class ScanStorePointCalculator implements PointCalculator
{
    public function calculate($amount = null): int
    {
        return 50;
    }
}
