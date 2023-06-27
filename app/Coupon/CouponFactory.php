<?php

namespace App\Coupon;

use App\Coupon\Senders\CouponSender;
use App\Coupon\Senders\EmailCouponSender;
use App\Coupon\Senders\SMSCouponSender;
use App\Coupon\Type\BirthdayCoupon;
use App\Coupon\Type\ICoupon;
use App\Coupon\Type\InformationCompleteCoupon;
use App\Coupon\Type\OpenCardCoupon;
use App\Coupon\Type\SleepingCoupon;
use App\Coupon\Type\SpecialCoupon;
use InvalidArgumentException;

class CouponFactory
{
    public static function create($type, $couponModel, $customer): ICoupon
    {
        return match ($type) {
            CouponEnums::TYPE_BIRTHDAY => new BirthdayCoupon($couponModel, $customer),
            CouponEnums::TYPE_SLEEPING => new SleepingCoupon($couponModel, $customer),
            CouponEnums::TYPE_INFORMATION_COMPLETE => new InformationCompleteCoupon($couponModel, $customer),
            CouponEnums::TYPE_OPEN_CARD => new OpenCardCoupon($couponModel, $customer),
            CouponEnums::TYPE_SPECIAL => new SpecialCoupon($couponModel, $customer),
            default => throw new InvalidArgumentException('Invalid coupon type.'),
        };
    }
}
