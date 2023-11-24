<?php

namespace App\Stamp;

use App\Enums\StatusCode;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Stamp\Enums\StampDistribution;
use App\Stamp\Enums\Status;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class StampCardService
{
    public function grantStampForCustomer(StampDistribution $strategy, $customer, $quantity, $expiredAt = null, $store = null, $operator = null, $memo = null, $consumedAt = null): void
    {
        $stampCard = new StampCard();
        $stampCard->setDistributionStrategy($strategy);
        $stampCard->setCustomer($customer);
        $stampCard->setExpirationDate($expiredAt);
        $stampCard->setStore($store);
        $stampCard->setOperator($operator);
        $stampCard->setConsumedAt($consumedAt);

        $stampCard->distributeStamp($quantity, $memo);
    }
}
