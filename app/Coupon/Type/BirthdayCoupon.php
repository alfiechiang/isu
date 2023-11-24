<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;
use App\Coupon\CouponFactory;
use App\Models\CouponCustomer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BirthdayCoupon extends ACoupon implements ICoupon
{
    protected string $prefixType = CouponEnums::TYPE_BIRTHDAY;
    protected int $birthdayMonth;

    public function __construct($couponModel, $customer)
    {
        parent::__construct($couponModel, $customer);

        $this->birthdayMonth = 2;
    }

    private function hasExistCoupon(): bool
    {
        $expired = Carbon::today();

        return $this->customer->whereHas('coupons', function (Builder $query) use ($expired) {
            return $query->where('expired_at', '>', $expired)
                ->whereHas('coupon', function (Builder $query) {
                    return $query->where('type', CouponEnums::TYPE_BIRTHDAY);
                });
        })->exists();
    }

    public function isAvailable(): bool
    {
        if($this->customer->birthday == '') {
            return false;
        }

        $birthday = Carbon::parse($this->customer->birthday);
        // 取得今年的生日日期
        $currentYearBirthday = $birthday->copy()->year(date('Y'));

        // 如果今年的生日已經過了，則使用明年的生日日期
        if ($currentYearBirthday->lt(Carbon::now())) {
            $currentYearBirthday->addYear();
        }

        // 取得今天的日期
        $today = Carbon::today();

        // 取得兩個月後的日期
        $targetMonthsLater = $today->copy()->addMonths(2);

        return $currentYearBirthday->between($today, $targetMonthsLater) && !$this->hasExistCoupon();
    }

    public function getDescription(): string
    {
        return '生日' . $this->birthdayMonth . '個月內顧客即可獲得優惠券';
    }
}

