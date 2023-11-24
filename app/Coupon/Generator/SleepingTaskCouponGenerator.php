<?php

namespace App\Coupon\Generator;

use App\Coupon\CouponEnums;
use App\Coupon\CouponService;
use App\Models\Coupon;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SleepingTaskCouponGenerator extends CouponGenerator implements ICouponGenerator
{
    public function getLongTermInactiveCustomers($executionDate)
    {
        $sixMonthsAgo = $executionDate->subMonths(6);
        $expired = $executionDate;

        return Customer::where(function (Builder $query) {
            $query->whereHas('stamps')->orWhereHas('points');
        })->whereDoesntHave('stamps', function (Builder $query) use ($sixMonthsAgo) {
            return $query->where('created_at', '>', $sixMonthsAgo);
        })->whereDoesntHave('points', function (Builder $query) use ($sixMonthsAgo) {
            return $query->where('created_at', '>', $sixMonthsAgo);
        })->whereDoesntHave('coupons', function (Builder $query) use ($expired) {
            return $query->where('expired_at', '>', $expired)
                ->whereHas('coupon', function (Builder $query) {
                    return $query->where('type', CouponEnums::TYPE_SLEEPING);
                });
        });
    }

    public function execution(?Carbon $executionDate): void
    {
        if (!$executionDate instanceof Carbon) {
            throw new \InvalidArgumentException('Invalid execution date.');
        }

        $longTermInactiveCustomers = $this->getLongTermInactiveCustomers($executionDate);

        $memo = null;

        $this->paginateAndProcessModels($longTermInactiveCustomers, function ($customer) use ($memo) {
            $this->couponService->generateCouponsForCustomer(CouponEnums::TYPE_SLEEPING, $customer, $memo);
        });
    }
}
