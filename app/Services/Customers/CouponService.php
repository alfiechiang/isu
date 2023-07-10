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
            $Builder = $Builder->where('expired_at', '<=', $now)
            ->orderBy('created_at', 'desc')
            ->orWhere('status', 2)
            ->where('customer_id', $auth->id)
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
            $Builder = $Builder->where('status', $data['status'])
                ->orderBy('created_at', 'desc');
        }

        if ($data['status'] == 2) {
            $Builder = $Builder->where('expired_at', '<=', $now)
                ->orderBy('created_at', 'desc')
                ->orWhere('status', 2)
                ->where('customer_id', $auth->id)
                ->orderBy('created_at', 'desc');
        }

        return $Builder->paginate($data['per_page']);
    }
}