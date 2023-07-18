<?php

namespace Database\Seeders;

use App\Models\Prize;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreEmployee::truncate();
        DB::table('stores')->delete(); 
        StoreEmployee::create([
            'uid'=>'ISU'. rand(100000, 999999),
            'email'=>'i44711015@gmail.com',
            'password'=>'12345678',
            'name'=>'才敢讚聲',
            'role_id'=>1,
        ]);

        $store=Store::create([
            'name'=>"測試商店"
        ]);
       $employee= StoreEmployee::create([
            'uid'=>'ISU'. rand(100000, 999999),
            'email'=>'k44711015@gmail.com',
            'password'=>'12345678',
            'name'=>'碳水',
            'role_id'=>2,
            'store_id'=>$store->id
        ]);
        $this->prize($employee->uid);
        
    }

    public function prize($store_uid){
        Prize::truncate();
        $insertData=[
            ["store_uid"=>$store_uid,"exchange_num"=>5,"spend_stamp_num"=>3,"stock"=>10,"item_name"=>"小米"],
            ["store_uid"=>$store_uid,"exchange_num"=>5,"spend_stamp_num"=>3,"stock"=>10,"item_name"=>"iphone"],
            ["store_uid"=>$store_uid,"exchange_num"=>5,"spend_stamp_num"=>3,"stock"=>10,"item_name"=>"Switch"],
            ["store_uid"=>$store_uid,"exchange_num"=>5,"spend_stamp_num"=>3,"stock"=>10,"item_name"=>"礦泉水"],
            ["store_uid"=>$store_uid,"exchange_num"=>5,"spend_stamp_num"=>3,"stock"=>10,"item_name"=>"毛巾"],
        ];
        Prize::insert($insertData);
    }

    
}
