<?php

namespace Database\Seeders;

use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\PointCustomer;
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
        CouponCustomer::truncate(); 
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
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-11-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-11-25 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-12-25 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-12-16 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-06-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-05-30 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => null],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-09-22 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => '2023-03-01 23:59:59'],
            ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-09-22 23:59:59', 'reference_type' => '系統發放', 'type' => 1, 'consumed_at' => '2023-03-01 23:59:59'],
        ];
        StampCustomer::insert($insertData);
        $this->pointCustomers($id);
        $this->couponCustomers($id);
    }

    private function pointCustomers($id){
        ## type 1 進店掃描 2消費認證
        $insertData=[
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-07-07 12:35:30','source'=>'北門喔薄褸','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-05-07 12:35:30','source'=>'嘿嘿嘿','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-06-11 12:35:30','source'=>'其實我很Ｘ','type'=>1,'value'=>50],
            ['id'=>Str::uuid(),'customer_id'=>$id,'created_at'=>'2023-02-07 12:35:30','source'=>'背包41','type'=>1,'value'=>60],
        ];

        PointCustomer::insert($insertData);
    }

    private function couponCustomers($id){
        $insertData=[
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>1,'coupon_cn_name'=>'生日大禮','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-08-07 12:35:30','coupon_id'=>'B20230707','customer_id'=>$id],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>1,'coupon_cn_name'=>'好久不見','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-08-07 12:35:30','coupon_id'=>'F20230707','customer_id'=>$id],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'開卡禮','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','coupon_id'=>'C20230707','customer_id'=>$id],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'會員大禮包','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','coupon_id'=>'M20230707','customer_id'=>$id],
        ];

        CouponCustomer::insert($insertData);

    }

}


