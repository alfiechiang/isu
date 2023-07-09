<?php

namespace Database\Seeders;

use App\Models\StorePrivilegeRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorePrivilegeRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        StorePrivilegeRole::truncate();
        $insertData=[
            ['name'=>'TOP','cn_name'=>'最高權限'],
            ['name'=>'STORE','cn_name'=>'店家權限'],
            ['name'=>'COUNTER','cn_name'=>'櫃檯權限']
        ];
        StorePrivilegeRole::insert($insertData);
    }
}
