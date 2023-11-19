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

}
