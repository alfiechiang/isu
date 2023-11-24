<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;
use App\Models\CouponCustomer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class InformationCompleteCoupon extends ACoupon implements ICoupon
{
    protected string $prefixType = CouponEnums::TYPE_INFORMATION_COMPLETE;

    private function hasExistCoupon(): bool
    {
        return CouponCustomer::where('customer_id', $this->customer->id)
            ->whereHas('coupon', function (Builder $query) {
                return $query->where('type', $this->prefixType);
            })->exists();
    }

    public function isAvailable(): bool
    {
        $requiredFields = ['name', 'email', 'phone', 'gender', 'citizenship', 'avatar', 'birthday', 'address', 'interest'];

        foreach ($requiredFields as $field) {
            if (($this->customer->{$field}) == '') {
                return false;
            }
        }

        return !$this->hasExistCoupon();
    }

    public function getDescription(): string
    {
        return '顧客完整填寫資料即可獲得優惠券';
    }
}
