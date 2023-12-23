<?php

namespace App\Services\Customers;

use App\Exceptions\ErrException;
use App\Models\Customer;
use App\Models\StampCustomer;
use Illuminate\Support\Facades\Auth;
use App\Enums\StampCustomerType;
use App\Models\CouponCustomer;

class CouponService
{

    public function list($data)
    {
        $Builder = new  CouponCustomer();
        $auth = Auth::user();

        $Builder = $Builder->where('customer_id', $auth->id);
        $now = date('Y-m-d H:i:s');

        if ($data['status'] == 1) {
            $Builder = $Builder->where('status', $data['status'])
                ->orderBy('created_at', 'desc');
        }

        if ($data['status'] == 2) {
            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expired_at', '<=', $now)
                    ->orWhere('status', 2);
            })
                ->orderBy('created_at', 'desc');
        }

        return $Builder->get();
    }

    public function pageList($data)
    {

        $Builder = new  CouponCustomer();
        $auth = Auth::user();
        $Builder = $Builder->where('customer_id', $auth->id);
        $now = date('Y-m-d H:i:s');

        if ($data['status'] == 1) {
            //檢查日期是否過期 但是status還是1
            $checkutList = $Builder->where('status', $data['status'])
                ->orderBy('created_at', 'desc')->get();
            foreach ($checkutList as $check) {
                $now = date('Y-m-d H:is');
                if ($check->expired_at < $now) {
                    $check->status = 2; //過期以使用
                    $check->save();
                }
            }

            $Builder = $Builder->where('status', $data['status'])
                ->orderBy('created_at', 'desc');
        }

        if ($data['status'] == 2) {
            $Builder = $Builder->where(function ($query) use ($now) {
                $query->where('expired_at', '<=', $now)
                    ->orWhere('status', 2);
            })
                ->orderBy('created_at', 'desc');
        }

        return $Builder->with('coupon')->paginate($data['per_page']);
    }

    public function findCutstmerCouponOne()
    {
        $auth = Auth::user();
        $Builder = new  CouponCustomer();

        return  $Builder->where('customer_id', $auth->id)->where('coupon_id', config('coupon.customer.coupon_id'))
            ->first();
    }
}
