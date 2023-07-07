<?php

namespace App\Services\Customers;

use App\Exceptions\ErrException;
use App\Models\Customer;
use App\Models\StampCustomer;
use Illuminate\Support\Facades\Auth;

class StampService
{

    public function list($data)
    {

        $Builder = new  StampCustomer();
        $now = date('Y-m-d H:i:s');

        if ($data['search'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
                ->where('expired_at', '>=', $now)->whereNull('consumed_at');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {
            $Builder = $Builder->where('expired_at', '<=', $now)->whereNotNull('consumed_at');;
        }

        $auth =Auth::user();

        return $Builder->where('customer_id',$auth->id)->get();
    }

    public function pageList($data)
    {

        $Builder = new  StampCustomer();
        $now = date('Y-m-d H:i:s');

        if ($data['search'] == 'USE') {
            $Builder = $Builder->where('created_at', '<=', $now)
                ->where('expired_at', '>=', $now)->whereNull('consumed_at')
                ->orderBy('expired_at', 'asc');
        }

        if ($data['search'] == 'HAVE_USE_OR_EXPIRE') {
            $Builder = $Builder->where('expired_at', '<=', $now)->whereNotNull('consumed_at')
                ->orderBy('consumed_at', 'desc');
        }

        $auth =Auth::user();

        return $Builder->where('customer_id',$auth->id)->paginate($data['per_page']);
    }

    #贈送集章
    public function deliver($data)
    {
        $cutomer =Customer::where('guid',$data['guid'])->first();

        if(is_null( $cutomer)){
            throw new ErrException("該ID不存在");
        }
        $to_cutomer=$cutomer->id;
        $auth =Auth::user();
        $from_cutomer=$auth->id;
        $stamps=StampCustomer::where('customer_id', $from_cutomer)->get();

        if ($data['stamp_num'] > $stamps->count()) {
            throw new ErrException("數量不足");
        }
        
        StampCustomer::where('customer_id', $from_cutomer)->limit($data['stamp_num'])
            ->orderBy('expired_at', 'asc')->update(['customer_id' => $to_cutomer]);
    }
}
