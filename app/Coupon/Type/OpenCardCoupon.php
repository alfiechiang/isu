<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;

class OpenCardCoupon extends ACoupon implements ICoupon
{
    protected string $prefixType = CouponEnums::TYPE_OPEN_CARD;

    public function isAvailable(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return '顧客開卡即可獲得優惠券';
    }
}
