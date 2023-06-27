<?php

namespace App\Stamp\Distribution;

use App\Models\StampCustomer;
use App\Models\StoreEmployee;
use App\Stamp\Enums\StampDistribution;
use App\Stamp\StampCard;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class ADistributionStrategy implements DistributionStrategy
{
    protected StampCard $stampCard;

    public function __construct(StampCard $stampCard)
    {
        $this->stampCard = $stampCard;
    }

    public function createStamp($quantity, $customer, $expired_at, $memo = null): StampCustomer
    {
        try {
            DB::beginTransaction();;

            $customer->stamps += $quantity;

            $stampCustomer = new StampCustomer([
                'customer_id' => $customer->id,
                'expired_at' => $expired_at,
                'consumed_at' => $this->stampCard->getConsumedAt(),
                'is_redeem' => $quantity < 0,
                'remain_quantity' => $customer->stamps,
                'value' => $quantity,
                'memo' => $memo ?? null,
                'type' => $this->getDistributionType(),
            ]);

            $operator = $this->stampCard->getOperator();
            $stampCustomer->operator()->associate($operator);

            $store = $this->stampCard->getStore();
            $stampCustomer->store()->associate($store);

            $stampCustomer->save();

            $customer->save();

            DB::commit();

            return $stampCustomer;
        } catch (\Exception $err) {
            DB::rollBack();

            throw new Exception($err);
        }
    }

    public function getRedeemableRewards()
    {

    }

    abstract public function getDistributionType(): string;
}


