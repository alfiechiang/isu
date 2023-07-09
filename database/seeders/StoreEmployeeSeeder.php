<?php

namespace Database\Seeders;

use App\Models\StoreEmployee;
use App\Models\StorePrivilegeRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreEmployee::truncate();
        $role=StorePrivilegeRole::where('name','TOP')->first();
        StoreEmployee::create([
            'email'=>'i44711015@gmail.com',
            'password'=>'12345678',
            'role_id'=>$role->id
        ]);
    }
}
