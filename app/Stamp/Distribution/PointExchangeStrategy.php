<?php

namespace App\Stamp\Distribution;

use App\Models\StampCustomer;
use App\Models\StoreEmployee;
use App\Point\PointService;
use App\Stamp\Enums\StampDistribution;
use App\Stamp\StampCard;
use Carbon\Carbon;

class PointExchangeStrategy extends ADistributionStrategy
{
    protected PointService $pointService;

    const CONVERSION_POINT = 1000;

    public function __construct(StampCard $stampCard, PointService $pointService)
    {
        parent::__construct($stampCard);
        $this->pointService = $pointService;
    }

    /**
     * @throws \Exception
     */
    public function distributeStamp($quantity, $memo): bool
    {
        $customer = $this->stampCard->getCustomer();
        $points = $quantity * self::CONVERSION_POINT;

        if($quantity < 1){
            throw new \Exception("數量必須大於 0");
        }

        if ($this->canRedeem($quantity)) {
            $this->pointService->deductPoints($customer, $points, "兌換 {$quantity} 個集章，消耗點數 {$points}");

            $this->createStamp($quantity, $customer, $this->getExpirationDate(), $memo);

            return true;
        } else {
            throw new \Exception("無法進行兌換，剩餘點數 {$this->pointService->getPointBalance($customer)}");
//            return false;
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
        $customer = $this->stampCard->getCustomer();
        $points = $quantity * self::CONVERSION_POINT;

        return $this->pointService->hasEnoughPoints($customer, $points);
    }

    public function getDistributionType(): string
    {
        return StampDistribution::POINT->value;
    }
}


