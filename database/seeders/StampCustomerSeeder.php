<?php

namespace Database\Seeders;

use App\Models\StampCustomer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StampCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        StampCustomer::truncate();
        ##type 1 點數兌換
        $insertData =[
            ['created_at'=>'2023-05-15 23:59:59','expired_at'=>'2023-07-30 23:59:59','reference_type'=>'系統發放','type'=>1,'consumed_at'=>null],
            ['created_at'=>'2023-05-15 23:59:59','expired_at'=>'2023-07-25 23:59:59','reference_type'=>'系統發放','type'=>1,'consumed_at'=>null],
           ['created_at'=>'2023-05-15 23:59:59','expired_at'=>'2023-07-30 23:59:59','reference_type'=>'系統發放','type'=>1,'consumed_at'=>'2023-07-05 23:59:59'],
           ['created_at'=>'2023-05-15 23:59:59','expired_at'=>'2023-07-30 23:59:59','reference_type'=>'系統發放','type'=>1,'consumed_at'=>'2023-07-03 23:59:59'],
        ];
        StampCustomer::insert($insertData);

    }
}
