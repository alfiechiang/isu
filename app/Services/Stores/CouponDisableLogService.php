<?php

namespace App\Services\Stores;

use App\Models\County;
use App\Models\CouponDisableLog;

class CouponDisableLogService
{
    public function customerSpecificCouponPageList($data){
       return  CouponDisableLog::where("guid",$data['guid'])->where("coupon_code",$data['coupon_code'])->paginate($data['per_page']);
    }

}
