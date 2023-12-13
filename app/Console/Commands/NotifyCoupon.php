<?php

namespace App\Console\Commands;

use App\Models\CustomCoupon;
use App\Models\CustomCouponCustomer;
use App\Models\Customer;
use App\Models\Otp;
use App\Notifications\RegisterVerifyOtp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('exec notify:coupon');
        $codes=CustomCoupon::where('notify',1)->pluck('code');
        $customCouponCustomers=CustomCouponCustomer::whereIn('coupon_code',$codes)->where('notify',0)->limit(10)->get();
        foreach($customCouponCustomers as $customCouponCustomer){
           $customer=Customer::where('guid',$customCouponCustomer->guid)->first();
           $otp= Otp::create([
                //'coupon_code'=>$customCouponCustomer->coupon_code,
                'identifier'=>$customer->phone
            ]);
            $validity=0;
            $lang='cn';
            $otp->country_code = "+886";
            $otp->notify(new RegisterVerifyOtp($validity, $lang));
        }
    }
}
