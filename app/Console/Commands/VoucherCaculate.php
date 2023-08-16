<?php

namespace App\Console\Commands;

use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\StampCustomer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class VoucherCaculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:caculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       //$this->birthdayCoupon();
       $this->longtimeCoupon();
    }

    public function longtimeCoupon(){
        $customer_ids=[];
        Customer::chunk(1000,function ($chunk) use(&$customer_ids){
            foreach($chunk as $record){
                $customer_ids[]=$record->id;
            }
        });

        $today =date('Y-m-d H:i:s');
        $starttime = date('Y-m-d', strtotime("-180 days", strtotime($today)));
        $endtime =$today;

        ## 180天內兌換集章會員
        $exchange1_ids=StampCustomer::whereBetween('consumed_at', [$starttime, $endtime])->pluck('customer_id');

        ## 180天內兌換優惠卷會員
        $exchange2_ids=CouponCustomer::whereBetween('consumed_at', [$starttime, $endtime])->pluck('customer_id');

        #排除兌換過的會員 就是未兌換會員
        $collection=collect($customer_ids);
        $diff = $collection->diff($exchange1_ids);
        $collection=collect($diff->all());
        $diff = $collection->diff($exchange2_ids);

        #排除這180內已發送過的
        $longtime_customer_ids=$diff->all();
        $already_send_customer_ids=CouponCustomer::where('coupon_id',config('coupon.longtime.coupon_id'))
        ->whereIn('customer_id',$longtime_customer_ids)
        ->whereBetween('created_at', [$starttime, $endtime])->pluck('customer_id');
        $collection=collect($longtime_customer_ids);
        $diff = $collection->diff($already_send_customer_ids);
        $send_longtime_customer_ids=$diff->all();

        #發送
        $insertData=[];
        $created_at=date('Y-m-d H:i:s');
        $expire_at = date('Y-m-d', strtotime("+180 days", strtotime($today)));
        foreach($send_longtime_customer_ids as $send_longtime_customer_id){
            $data=[
                'id'=>Str::uuid(),
                'code_script'=>'F'.date('Ymd'),
                'created_at'=>$created_at,
                'expired_at'=>$expire_at ,
                'status'=>1,
                'coupon_cn_name'=>'好久不見',
                'customer_id'=>$send_longtime_customer_id,
                'coupon_id'=>config('coupon.longtime.coupon_id')
            ];
            $insertData[]=$data;
        }
        DB::table('coupon_customers')->insert($insertData);
    }

    public function birthdayCoupon(){
        $created_at = date('Y-m-d');
        $birthday = date('Y-m-d', strtotime("+2 month", strtotime($created_at)));
        $expire_at = date('Y-m-d', strtotime("+1 month", strtotime($birthday)));
        $customers = Customer::where('birthday', 'LIKE', '%' . $birthday . '%')->get();
        $customer_ids = $customers->pluck('id');
        $year_start_date = now()->firstOfYear()->format('Y-m-d');
        $year_end_date = now()->endOfYear()->format('Y-m-d');
        $diff2 = CouponCustomer::whereIn('customer_id', $customer_ids)->whereBetween('created_at', [$year_start_date, $year_end_date])
            ->where('coupon_id', config('coupon.birthday.coupon_id'))->pluck('customer_id');
        $diff1 = collect($customer_ids);
        $birth_coupon_customers = $diff1->diff($diff2);
        $insertData=[];
        foreach($birth_coupon_customers as $birth_coupon_customer){
            $data=[
                'id'=>Str::uuid(),
                'code_script'=>'B'.date('Ymd'),
                'created_at'=>$created_at,
                'expired_at'=>$expire_at ,
                'status'=>1,
                'coupon_cn_name'=>'生日大禮',
                'customer_id'=>$birth_coupon_customer,
                'coupon_id'=>config('coupon.birthday.coupon_id')
            ];
            $insertData[]=$data;
        }
        DB::table('coupon_customers')->insert($insertData);
    }
}
