<?php

namespace App\Services\Customers;

use Illuminate\Support\Facades\Auth;
use App\Models\CouponCustomer;
use App\Models\CustomCoupon;
use App\Models\CustomCouponCustomer;
use Illuminate\Support\Facades\DB;

class CustomCouponService
{

    public function customerPageList($data)
    {
        DB::enableQueryLog();

        
        $Builder = new  CustomCouponCustomer();
        $auth = Auth::user();
        $Builder = $Builder->where('guid', $auth->guid);
        $now = date('Y-m-d H:i:s');
        
        if ($data['status'] == 1) {
            $Builder = $Builder->where('exchange', 0)
                ->where('expire_time','>',$now)
                ->orderBy('created_at', 'desc');
        }

        if ($data['status'] == 2) {
            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expire_time', '<', $now)->orWhere('exchange',1);
            })
                ->orderBy('created_at', 'desc');
        }

        $res= $Builder->with('coupon')->paginate($data['per_page']);

        $query = DB::getQueryLog();

        dd($query);


        return $res;

    }

}
