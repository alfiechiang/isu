<?php

namespace App\Coupon;

use App\Enums\StatusCode;
use App\Helpers\Utils;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\CouponCustomer;
use App\Models\CouponCustomerRedeem;
use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CouponService
{
    public function generateCouponsForCustomer(string $type, $customer, $memo = null, $reference = null): void
    {
        $availableCoupons = $this->findAvailableCouponsByType($type);

        foreach ($availableCoupons as $coupon) {
            $coupon = CouponFactory::create($type, $coupon, $customer);

            if($coupon->isAvailable()) {
                $coupon->issueCoupon($memo, $reference);
            }
        }
    }

    public function findAvailableCouponsByType(string $type) {
        return Coupon::where(function ($query) {
            $query->whereNull('start_at')->orWhere('start_at', '<=', now());
        })->where(function ($query) {
            $query->whereNull('end_at')->orWhere('end_at', '>=', now());
        })->where('type', $type)->get();
    }

    /**
     * @throws Exception
     */
    public function redeemCoupon(string $code, $memo): CouponCustomer
    {
        $couponCode = CouponCode::where('code', $code)->first();

        if (!$couponCode) {
            throw new Exception('找不到優惠券.', StatusCode::MODEL_NOT_EXIST->value);
        }

        if (!$couponCustomer = $couponCode->code_customer) {
            throw new Exception('該優惠券未綁定任何顧客.', StatusCode::COUPON_UNBOUND_USER->value);
        }

        if ($couponCustomer->expired_at != '' && $couponCustomer->expired_at < now()) {
            throw new Exception('該優惠券已經過期.', StatusCode::COUPON_EXPIRED->value);
        }

        if ($couponCustomer->status == CouponEnums::STATUS_USED) {
            throw new Exception('該優惠券已經使用.', StatusCode::COUPON_ALREADY_USED->value);
        }

        try {
            DB::beginTransaction();;

            $couponCustomer->fill([
                'status' => CouponEnums::STATUS_USED
            ]);

            $redeem = new CouponCustomerRedeem([
                'memo' => $memo,
                'coupon_customer_id' => $couponCustomer->id,
            ]);

            $reference = StoreEmployee::first();
            if($reference){
                $redeem->operator()->associate($reference);
            }

            if($reference->store) {
                $redeem->reference()->associate($reference->store);
            }

            $redeem->save();
            $couponCustomer->save();

            DB::commit();

            return $couponCustomer;
        } catch (\Exception $err) {
            DB::rollBack();

            throw new Exception($err);
        }
    }
}
