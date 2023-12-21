<?php

namespace App\Services\Stores;

use App\Models\Coupon;
use App\Models\CouponCustomer;
use App\Models\Customer;

class CouponService
{

    public function pageList($data){
        return Coupon::paginate($data['per_page']);
    }


    public function peoplePageList($data,$coupon_code){

        $Builder = CouponCustomer::where('coupon_id', $coupon_code);
        if (isset($data['keyword'])) {
            $customer_id = '';
            $customer = Customer::where('guid', $data['keyword'])->orWhere('name', $data['keyword'])->first();
            if (!is_null($customer)) {
                $customer_id = $customer->id;
            }
            $Builder = $Builder->where('customer_id', $customer_id);
        }

        return $Builder->with('customer')
            ->groupBy('customer_id', 'coupon_id')->paginate($data['per_page']);
    }


    public function findoneCouponByMember($coupon_code,$coupon_id){

        $coupon =CouponCustomer::where('coupon_id',$coupon_code)->with('coupon')
        ->where('id',$coupon_id)->first();
        $now =date('Y-m-d H:i:s');
        if(isset($coupon->expired_at)){
            if($coupon->expired_at <$now){
                $coupon->status=2;
                $coupon->save();
            }
        }

       

        return $coupon;
    }

}
