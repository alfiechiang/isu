<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PointCustomer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StampCustomer;
use Illuminate\Support\Str;



class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('customers')->delete(); 
        StampCustomer::truncate();
        PointCustomer::truncate();

               //        //rice001  911902985 password 123456
        $r1=Customer::create(
            ['name' => 'rice001', 'guid' => 'ISU202307071234567', 'phone' => '911902985', 'password' => '123456']
        );
        $this->stampCustomers($r1->id);

        $r2=Customer::create(
            ['name' => 'rice002', 'guid' => 'ISU202307071234566', 'phone' => '911902984', 'password' => '123456']
        );
        $this->stampCustomers($r2->id);

       $r3= Customer::create(
            ['name' => 'zack', 'guid' => 'ISU202307051234566', 'phone' => '938905078', 'password' => '123456']
        );
        $this->stampCustomers($r3->id);

        $r4=Customer::create(
            ['name' => 'rice003', 'guid' => 'ISU202307051234569', 'phone' => '938905079', 'password' => '123456']
        );
        $this->stampCustomers($r4->id);

    }

    private function stampCustomers($id){
        $insertData = [
            ['customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-07-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-08-25 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-09-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => '2023-07-05 23:59:59'],
            ['customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-10-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => '2023-07-03 23:59:59'],
        ];
        StampCustomer::insert($insertData);
        $this->pointCustomers(($id));
    }

    private function pointCustomers($id){
        ## type 1 進店掃描 2消費認證
        $insertData=[
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>1314],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>1000],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>200],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>200],
        ];

        PointCustomer::insert($insertData);

    }


}


