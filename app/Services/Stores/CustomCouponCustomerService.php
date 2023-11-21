<?php

namespace App\Services\Stores;

use App\Models\CouponCustomer;
use App\Models\CustomCouponCustomer;
use App\Models\Customer;

class CustomCouponCustomerService
{


    public function peoplePageList($data,$coupon_code)
    {
      return  CustomCouponCustomer::where('coupon_code',$coupon_code)->paginate($data['per_page']);
    }



    public function pageList($data)
    {

        $res=[];
        switch($data['category']){
            case 'custom':
                $res=$this->customPageList($data);
                break;
            case 'others':
                $res=$this->otherPageList($data);
                break;
        }

        return $res;

    }

    private function otherPageList($data){ //其他非客製化

        $customer=Customer::where('guid',$data['guid'])->first();
        $customer_id=$customer->id;
        $Builder = CouponCustomer::where('customer_id',$customer_id);

        if (isset($data['coupon_code'])) {
            $Builder=$Builder->where('coupon_id', $data['coupon_code']);
        }

        //狀態 1可使用 2已使用
        if (isset($data['type'])) {

            if ($data['type'] == 1) { //可使用
                $Builder = $Builder->where('status', 1)->where('expired_at', '>', date('Y-m-d H:i:s'));
            }
            if ($data['type'] == 2) {
                $Builder = $Builder->where('status', 2);
            }
            if ($data['type'] == 3) {
                $Builder = $Builder->where('expired_at', '<', date('Y-m-d H:i:s'));
            }
        }

       return $Builder->paginate($data['per_page']);

    }


    private function customPageList($data){

        $Builder = CustomCouponCustomer::where('guid', $data['guid']);
        if (isset($data['coupon_code'])) {
            $Builder=$Builder->where('coupon_code', $data['coupon_code']);
        }

        if (isset($data['type'])) {
            if ($data['type'] == 1) { //可使用
                $Builder = $Builder->where('exchange', 0);
            }
            if ($data['type'] == 2) {
                $Builder = $Builder->where('exchange', 1);
            }
            if ($data['type'] == 3) {
                $Builder = $Builder->where('expire_time', '<', date('Y-m-d H:i:s'));
            }
        }


        return $Builder->paginate($data['per_page']);

    }


}