<?php

namespace App\Console\Commands;

use App\Enums\StampCustomerType;
use App\Models\StampCustomer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateStampType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatestamptype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '檢查集章類型狀態';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('exec updatestamptype');
        $date = date('Y-m-d');
        $startTime = date('Y-m-d H:i:s', strtotime("-7 day", strtotime($date)));
        $endTime = date('Y-m-d H:i:s');
        StampCustomer::whereBetween('expired_at', [$startTime, $endTime])
            ->where('type', '!=', StampCustomerType::HAVE_EXPIRE->value)
            ->update(['type' => StampCustomerType::HAVE_EXPIRE->value]);
    }
}
