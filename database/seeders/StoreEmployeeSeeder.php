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
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'i44711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'k44711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);

        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'c44711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲2',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店2"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'd44711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水2',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);


        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'f44711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲3',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店3"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'g44711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水3',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);

        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'n44711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲4',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店4"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'l44711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水4',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);

        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'n34711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲5',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店5"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'l34711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水5',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);

        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'n24711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲6',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店6"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'l24711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水6',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);


        StoreEmployee::create([
            'uid' => 'ISU' . rand(100000, 999999),
            'email' => 'n74711015@gmail.com',
            'password' => '12345678',
            'name' => '才敢讚聲7',
            'role_id' => 1,
            'country_code' => '886'
        ]);

        $store = Store::create([
            'name' => "測試商店7"
        ]);

        $uid = 'ISU' . rand(100000, 999999);
        $employee = StoreEmployee::create([
            'uid' => $uid,
            'email' => 'l74711015@gmail.com',
            'password' => '12345678',
            'name' => '碳水7',
            'role_id' => 2,
            'store_id' => $store->id,
            'store_uid' => $uid,
            'country_code' => '886',
        ]);
        $this->prize($employee->uid);
    }

    public function prize($store_uid)
    {
        Prize::truncate();
        $insertData = [
            ["store_uid" => $store_uid, "exchange_num" => 5, "spend_stamp_num" => 3, "stock" => 10, "item_name" => "小米"],
            ["store_uid" => $store_uid, "exchange_num" => 5, "spend_stamp_num" => 3, "stock" => 10, "item_name" => "iphone"],
            ["store_uid" => $store_uid, "exchange_num" => 5, "spend_stamp_num" => 3, "stock" => 10, "item_name" => "Switch"],
            ["store_uid" => $store_uid, "exchange_num" => 5, "spend_stamp_num" => 3, "stock" => 10, "item_name" => "礦泉水"],
            ["store_uid" => $store_uid, "exchange_num" => 5, "spend_stamp_num" => 3, "stock" => 10, "item_name" => "毛巾"],
        ];
        Prize::insert($insertData);
    }
}
