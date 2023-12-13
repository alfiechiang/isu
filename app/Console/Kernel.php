<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('birthday-coupon:caculate')->dailyAt('00:05');
        $schedule->command('longtime-coupon:caculate')->dailyAt('00:07');
        $schedule->command('updatestamptype')->dailyAt('00:10');

        $schedule->command('notify:coupon')->everyHo('00:10');

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
