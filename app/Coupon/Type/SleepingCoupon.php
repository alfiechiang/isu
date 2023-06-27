<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;

class SleepingCoupon extends ACoupon implements ICoupon
{
    protected string $prefixType = CouponEnums::TYPE_SLEEPING;

    public function isAvailable(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return '半年內沒有消費的沉睡顧客即可獲得優惠券';
    }
}
