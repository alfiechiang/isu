<?php

namespace App\Stamp\Distribution;

use App\Models\StampCustomer;
use App\Models\StoreEmployee;
use App\Stamp\Enums\StampDistribution;
use App\Stamp\StampCard;
use Carbon\Carbon;

class StoreDistributionStrategy extends ADistributionStrategy
{
    /**
     * @throws \Exception
     */
    public function distributeStamp($quantity, $memo): bool
    {
        $customer = $this->stampCard->getCustomer();

        if ($this->canRedeem($quantity)) {
            if($quantity == 0){
                throw new \Exception("數量不能是 0");
            }

            $this->createStamp($quantity, $customer, $this->getExpirationDate(), $memo);

            return true;
        } else {
            return false;
        }
    }

    public function getExpirationDate(): Carbon
    {
        return $this->stampCard->getExpirationDate() ?? Carbon::now()->addYear();
    }

    /**
     * @throws \Exception
     */
    public function canRedeem($quantity): bool
    {
        return true;
    }

    public function getDistributionType(): string
    {
        return StampDistribution::STORE->value;
    }
}


