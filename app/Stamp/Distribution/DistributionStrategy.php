<?php

namespace App\Stamp\Distribution;

interface DistributionStrategy
{
    public function distributeStamp($quantity, $memo);
    public function getRedeemableRewards();
    public function getExpirationDate();
    public function canRedeem($quantity);
}

