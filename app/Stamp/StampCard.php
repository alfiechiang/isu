<?php

namespace App\Stamp;

use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\StampCustomer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Stamp\Distribution\DistributionStrategy;
use App\Stamp\Enums\StampDistribution;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class StampCard
{
    private DistributionStrategy $distributionStrategy;
    private ?Carbon $expirationDate;
    private $redeemableItem;
    private Customer $customer;
    private Store $store;
    private $operator;
    private $consumed_at;

    public function setDistributionStrategy(StampDistribution $distributionStrategy): void
    {
        $this->distributionStrategy = $distributionStrategy->create($this);
    }

    public function distributeStamp($quantity, $remark): void
    {
        $this->distributionStrategy->distributeStamp($quantity, $remark);
    }

    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function setStore($store): void
    {
        $this->store = $store;
    }

    public function getConsumedAt()
    {
        return $this->consumed_at;
    }

    public function setConsumedAt($consumed_at): void
    {
        $this->consumed_at = $consumed_at;
    }

    public function getOperator()
    {
        return $this->operator;
    }

    public function setOperator($operator): void
    {
        $this->operator = $operator;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setRedeemableItem($redeemableItem): void
    {
        $this->redeemableItem = $redeemableItem;
    }

    public function setExpirationDate($expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getExpirationDate():?Carbon {
        return $this->expirationDate;
    }
}
