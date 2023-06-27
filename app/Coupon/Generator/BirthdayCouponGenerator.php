<?php

namespace App\Coupon\Generator;

use App\Coupon\CouponService;
use App\Coupon\CouponEnums;
use App\Models\CouponCode;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BirthdayCouponGenerator extends CouponGenerator implements ICouponGenerator
{
    public function getFutureBirthdayCustomers($executionDate)
    {
        $expired = $executionDate;
        $thisMonth = $executionDate;
        $nextMonth = $executionDate->copy()->addMonth();
        $twoMonth = $executionDate->copy()->addMonth(2);

        return Customer::where(function (Builder $query) use ($thisMonth, $nextMonth, $twoMonth) {
            return $query->WhereMonth('birthday', $nextMonth->month)
                ->orWhereMonth('birthday', $twoMonth->month)
                ->orWhere(function (Builder $query) use ($thisMonth) {
                    return $query->whereMonth('birthday', $thisMonth->month)
                        ->whereDay('birthday', '>=', $thisMonth->day);
                });
        })->whereDoesntHave('coupons', function (Builder $query) use ($expired) {
            return $query->where('expired_at', '>', $expired)
                ->whereHas('coupon', function (Builder $query) {
                    return $query->where('type', CouponEnums::TYPE_BIRTHDAY);
                });
        });
    }

    public function execution(?Carbon $executionDate): void
    {
        if (!$executionDate instanceof Carbon) {
            throw new \InvalidArgumentException('Invalid execution date.');
        }

        $futureBirthdayCustomers = $this->getFutureBirthdayCustomers($executionDate);

        $memo = null;

        $this->paginateAndProcessModels($futureBirthdayCustomers, function ($customer) use ($memo) {
            $this->couponService->generateCouponsForCustomer(CouponEnums::TYPE_BIRTHDAY, $customer, $memo);
        });
    }
}
