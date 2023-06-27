<?php

namespace App\Console\Commands;

use App\Coupon\CouponEnums;
use App\Coupon\CouponFactory;
use App\Coupon\Generator\BirthdayCouponGenerator;
use App\Coupon\Generator\SleepingTaskCouponGenerator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateSleepingTaskCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:sleeping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected SleepingTaskCouponGenerator $couponGenerator;

    public function __construct(SleepingTaskCouponGenerator $generator)
    {
        parent::__construct();

        $this->couponGenerator = $generator;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        $this->couponGenerator->execution($today);
    }
}
