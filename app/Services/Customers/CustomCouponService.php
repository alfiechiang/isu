<?php

namespace App\Services\Customers;

use Illuminate\Support\Facades\Auth;
use App\Models\CouponCustomer;
use App\Models\CustomCoupon;
use App\Models\CustomCouponCustomer;

class CustomCouponService
{

    public function customerPageList($data)
    {
        $Builder = new  CustomCouponCustomer();
        $auth = Auth::user();
        $Builder = $Builder->where('guid', $auth->guid);

        if ($data['status'] == 1) {
            $Builder = $Builder->where('exchange', 0)
                ->orderBy('created_at', 'desc');
        }

        $now = date('Y-m-d H:i:s');
        if ($data['status'] == 2) {
            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expire_time', '<=', $now);
            })
                ->orderBy('created_at', 'desc');
        }

        return $Builder->with('coupon')->paginate($data['per_page']);
    }

}
