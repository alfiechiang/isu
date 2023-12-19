<?php

namespace App\Services\Stores;

use App\Models\Coupon;
use App\Models\CustomCouponCustomer;
use App\Models\CouponCustomer;
use App\Models\CustomCoupon;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class ExchangeCouponService
{

    //兌換優惠卷
    public function exchange($data){

        $coupon1 = Coupon::where("id", $data['coupon_code'])->first();
        $now =date('Y-m-d H:i:s');
        $store_name=Auth::user()->name;
        if (!is_null($coupon1)) {
            $customer = Customer::where('guid', $data['guid'])->first();
            CouponCustomer::where('id', $data['id'])
                ->where('customer_id', $customer->id)
                ->where('coupon_id', $data['coupon_code'])->update(
                    ['status' => 2,'consumed_at'=>$now,'exchange_store'=>$store_name]);
        }

        $coupon2 = CustomCoupon::where("code", $data['coupon_code'])->first();
        if (!is_null($coupon2)) {
            CustomCouponCustomer::where('id', $data['id'])
                ->where('coupon_code', $data['coupon_code'])
                ->where('guid', $data['guid'])
                ->update(['exchange' => 1,'exchange_time'=>$now,'exchange_place'=>$store_name]);
        }

    }

}
