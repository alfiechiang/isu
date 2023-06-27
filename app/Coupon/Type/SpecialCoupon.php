<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;

class SpecialCoupon extends ACoupon implements ICoupon
{
    protected string $prefixType = CouponEnums::TYPE_SPECIAL;

    public function isAvailable(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return '發放特別優惠券';
    }
}
