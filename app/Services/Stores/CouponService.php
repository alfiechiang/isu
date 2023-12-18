<?php

namespace App\Services\Stores;

use App\Models\Coupon;
use App\Models\CouponCustomer;

class CouponService
{

    public function pageList($data){
        return Coupon::paginate($data['per_page']);
    }


    public function peoplePageList($data,$coupon_code){
        return CouponCustomer::where('coupon_id',$coupon_code)->with('customer')
        ->groupBy('customer_id','coupon_id')->paginate($data['per_page']);
    }


    public function findoneCouponByMember($coupon_code,$coupon_id){

        $coupon =CouponCustomer::where('coupon_id',$coupon_code)->with('coupon')
        ->where('id',$coupon_id)->first();
        $now =date('Y-m-d H:i:s');

        if($coupon->expired_at <$now){
            $coupon->status=2;
            $coupon->save();
        }

        return $coupon;
    }

}
