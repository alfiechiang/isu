<?php

namespace App\Services\Stores;

use App\Exceptions\ErrException;
use App\Models\CustomCoupon;
use App\Models\CustomCouponCustomer;
use App\Models\CustomCouponPeopleList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CustomCouponService
{
    public function create($data)
    {
        $code = $data['code'] . date('Ymd');
        $operator =Auth::user()->email;
        $clientIp = request()->ip();
        $coupon=CustomCoupon::create([
            'name' => $data['name'],
            'code' => $code,
            'img' => $data['img'],
            'per_people_volume' => $data['per_people_volume'],
            'total_issue' => $data['total_issue'],
            'issue_time' => $data['issue_time'],
            'expire_time' => $data['expire_time'],
            'coupon_desc' => $data['coupon_desc'],
            'notice_desc' => $data['notice_desc'],
            'notify' => $data['notify'],
            'operator'=>$operator,
            'operator_ip'=>$clientIp
        ]);

        return ['coupon_code'=>$coupon->code];

    }

    public function findone($coupon_code)
    {
        return CustomCoupon::where('code', $coupon_code)->first();
    }

    public function send($coupon_code)
    { 
         //發放優戶卷
        $insertData = [];
        $items = CustomCouponPeopleList::where('coupon_code', $coupon_code)->get();
        $coupon =  CustomCoupon::where('code', $coupon_code)->first();

        foreach ($items as $item) {
            for ($i = 0; $i < $coupon->per_people_volume; $i++) {
                $data = [
                    'id' => Uuid::uuid4(),
                    'guid' => $item->guid,
                    'coupon_code' => $item->coupon_code,
                    'coupon_name' => $item->coupon_name,
                    'expire_time' => $coupon->expire_time
                ];
                $insertData[] = $data;
            }
        }

        DB::transaction(function () use ($insertData) {
            DB::table('custom_coupon_customers')->insert($insertData);
        });


    }

    public function update($coupon_code, $data)
    {
        return DB::transaction(function () use ($data, $coupon_code) {
            $coupon =  CustomCoupon::where('code', $coupon_code)->first();
            if( $coupon->shelve){
                throw new ErrException('此優惠卷已上架');
            }

            if (isset($data['code'])) {
                preg_match('/[a-zA-Z]+/', $data['code'], $text_matches);
                $text = $text_matches[0];
                // 匹配數字
                preg_match('/\d+/', $coupon->code, $number_matches);
                $number = $number_matches[0];
                $code = $text . $number;
                $data['code'] = $code;
                
                CustomCouponPeopleList::where('coupon_code', $coupon_code)->update(['coupon_code' => $code]);
            }

            $coupon->fill($data);
            $coupon->save();
            return ['coupon_code'=>$coupon->code];
        });
    }


    public function findoneCouponDisable($coupon_code,$operaterIp,$desc=null){

        DB::transaction(function () use ($coupon_code,$operaterIp,$desc) {
            $coupon =  CustomCoupon::where('code', $coupon_code)->first();
            $coupon->disable=true;
            CustomCouponCustomer::where('coupon_code',$coupon_code)->update(['disable'=>true,'desc'=>$desc]);
            $insertData=[];
            $auth =Auth::user();
            $coupons= CustomCouponCustomer::where('coupon_code',$coupon_code)->get();
            foreach($coupons as $coupon){
                $insertData[]=[
                    'guid'=>$coupon->guid,
                    'coupon_code'=>$coupon->coupon_code,
                    'coupon_name'=>$coupon->coupon_name,
                    'operator'=>$auth->name,
                    'disable_time'=>date('Y-m-d H:i:s'),
                    'operator_ip'=>$operaterIp,
                    'desc'=>$desc
                ];
            }

            DB::table('coupon_disable_logs')->insert($insertData);
        });

    }


    //特定會員某張優惠卷失效
    public function findoneCouponDisableByMember($coupon_code,$operaterIp,$desc,$coupon_id){

        DB::transaction(function () use ($coupon_code,$operaterIp,$desc,$coupon_id) {
            $coupon =  CustomCoupon::where('code', $coupon_code)->first();
            $coupon->disable=true;
            CustomCouponCustomer::where('coupon_code',$coupon_code)->where('id',$coupon_id)->update(['disable'=>true,'desc'=>$desc]);
            $insertData=[];
            $auth =Auth::user();
            $coupons= CustomCouponCustomer::where('coupon_code',$coupon_code)->where('id',$coupon_id)->get();
            foreach($coupons as $coupon){
                $insertData[]=[
                    'guid'=>$coupon->guid,
                    'coupon_code'=>$coupon->coupon_code,
                    'coupon_name'=>$coupon->coupon_name,
                    'operator'=>$auth->name,
                    'disable_time'=>date('Y-m-d H:i:s'),
                    'operator_ip'=>$operaterIp,
                    'desc'=>$desc
                ];
            }

            DB::table('coupon_disable_logs')->insert($insertData);
        });

    }

    public function findoneCouponByMember($coupon_code,$coupon_id){
       return  CustomCouponCustomer::where('coupon_code',$coupon_code)->with('coupon')
        ->where('id',$coupon_id)->first();
    }



    public function pageList($data)
    {
        return CustomCoupon::paginate($data['per_page']);
    }

}
