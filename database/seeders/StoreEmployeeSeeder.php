<?php

namespace Database\Seeders;

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
        
    }
}
