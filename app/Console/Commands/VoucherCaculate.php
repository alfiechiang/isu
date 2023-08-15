<?php

namespace App\Console\Commands;

use App\Models\CouponCustomer;
use App\Models\Customer;
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
       $this->birthdayCoupon();
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
