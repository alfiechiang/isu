<?php

namespace App\Console\Commands;

use App\Models\CustomCoupon as ModelsCustomCoupon;
use App\Services\Stores\CustomCouponService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-coupon';

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
        DB::transaction(function () {
            Log::info('exec custom-coupon');
            $starttime = date('Y-m-d 00:00:00');
            $now = date('Y-m-d H:is');
            $endtime = date('Y-m-d 23:59:59');
            $coupons = ModelsCustomCoupon::where('send', false)
                ->whereBetween('issue_time', [$starttime, $endtime])->get();
            $service = new CustomCouponService();
            foreach ($coupons as $coupon) {

                if ($coupon->issue_time <= $now) {
                    Log::info("exec:custom-coupon:foreach:$coupon->code");
                    $service->send($coupon->code);

                    $coupon->send = true;
                    $coupon->save();
                }
            }
        });
    }
}
