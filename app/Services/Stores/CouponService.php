<?php

namespace App\Services\Stores;

use App\Models\Coupon;
use App\Models\CouponCustomer;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponService
{

    public function pageList($data){
        return Coupon::paginate($data['per_page']);
    }


    public function peoplePageList($data,$coupon_code){

        $Builder = CouponCustomer::where('coupon_id', $coupon_code)->where('disable',false);
        if (isset($data['keyword'])) {
            $customer_id = '';
            $customer = Customer::where('guid', $data['keyword'])->orWhere('name', $data['keyword'])->first();
            if (!is_null($customer)) {
                $customer_id = $customer->id;
            }
            $Builder = $Builder->where('customer_id', $customer_id);
        }

        $list= $Builder->with('customer')->groupBy('customer_id', 'coupon_id')->get();
        foreach( $list as $item){
            if(is_null($item->customer)){
                CouponCustomer::where('id',$item->id)->delete();
            }
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

    //特定會員某張優惠卷失效
    public function findoneCouponDisableByMember($coupon_code, $operaterIp, $desc, $coupon_id)
    {

        DB::transaction(function () use ($coupon_code, $operaterIp, $desc, $coupon_id) {
            CouponCustomer::where('coupon_id', $coupon_code)->where('id', $coupon_id)
                ->update(['disable' => true, 'memo' => $desc,'consumed_at'=>date('Y-m-d H:i:s'),'status'=>2]);
            $insertData = [];
            $auth = Auth::user();
            $coupons = CouponCustomer::where('coupon_id', $coupon_code)->where('id', $coupon_id)->get();
            foreach ($coupons as $coupon) {
                $costomer =Customer::where('id',$coupon->customer_id)->first();
                $insertData[] = [
                    'guid' =>$costomer->guid,
                    'coupon_code' => $coupon->coupon_id,
                    'coupon_name' => $coupon->coupon_cn_name,
                    'operator' => $auth->name,
                    'disable_time' => date('Y-m-d H:i:s'),
                    'operator_ip' => $operaterIp,
                    'desc' => $desc
                ];
            }

            DB::table('coupon_disable_logs')->insert($insertData);
        });
    }

}
