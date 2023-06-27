<?php

namespace App\Coupon;

class CouponEnums
{
    const STATUS_UNUSED = 'unused';
    const STATUS_USED = 'used';

    const MODE_DRAW = 'draw';
    const MODE_SINGLE = 'single';
    const MODE_MULTI = 'multi';

    const TYPE_BIRTHDAY = 'birthday';
    const TYPE_SLEEPING = 'sleeping';
    const TYPE_OPEN_CARD = 'open_card';
    const TYPE_INFORMATION_COMPLETE = 'information_complete';
    const TYPE_SPECIAL = 'special';

    public static function mapTypes(): array
    {
        return [
            self::TYPE_BIRTHDAY,
            self::TYPE_SLEEPING,
            self::TYPE_OPEN_CARD,
            self::TYPE_INFORMATION_COMPLETE,
            self::TYPE_SPECIAL,
        ];
    }
}
