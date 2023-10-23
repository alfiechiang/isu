<?php

namespace App\Services\Stores;

use App\Models\CustomCouponCustomer;


class CustomCouponCustomerService
{

    public function pageList($data)
    {

        $Builder = CustomCouponCustomer::where('coupon_code', $data['coupon_code'])
        ->where('guid', $data['guid']);

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