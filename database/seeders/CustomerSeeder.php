<?php

namespace Database\Seeders;

use App\Models\Coupon;
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
            ['name' => 'rice002', 'guid' => 'ISU202307071735526', 'phone' => '911902984', 'password' => '123456']
        );
        $this->stampCustomers($r2->id);

       $r3= Customer::create(
            ['name' => 'zack', 'guid' => 'ISU202307051236012', 'phone' => '938905078', 'password' => '123456']
        );
        $this->stampCustomers($r3->id);

        $r4=Customer::create(
            ['name' => 'rice003', 'guid' => 'ISU202307051204569', 'phone' => '938905079', 'password' => '123456']
        );
        $this->stampCustomers($r4->id);

        $r5=Customer::create(
            ['name' => 'tensor01', 'guid' => 'ISU202307050206788', 'phone' => '975400413', 'password' => '123456']
        );
        $this->stampCustomers($r5->id);

        $r6=Customer::create(
            ['name' => 'tensor01', 'guid' => 'ISU202307050209603', 'phone' => '907891616', 'password' => '123456']
        );
        $this->stampCustomers($r6->id);

        $r7=Customer::create(
            ['name' => 'tensor02', 'guid' => 'ISU202307050202361', 'phone' => '985312895', 'password' => '123456']
        );
        $this->stampCustomers($r7->id);

        $r8=Customer::create(
            ['name' => 'tensor03', 'guid' => 'ISU202307050201350', 'phone' => '981373824', 'password' => '123456']
        );
        $this->stampCustomers($r8->id);

        $r9=Customer::create(
            ['name' => 'tensor04', 'guid' => 'ISU202307050206790', 'phone' => '929330276', 'password' => '123456']
        );
        $this->stampCustomers($r9->id);

        $r10=Customer::create(
            ['name' => 'tensor05', 'guid' => 'ISU2023070502064177', 'phone' => '965454825', 'password' => '123456']
        );
        $this->stampCustomers($r10->id);


        $r11=Customer::create(
            ['name' => 'tensor06', 'guid' => 'ISU2023070502060203', 'phone' => '994826389', 'password' => '123456']
        );
        $this->stampCustomers($r11->id);

        $r12=Customer::create(
            ['name' => 'tensor07', 'guid' => 'ISU2023070502061612', 'phone' => '967229640', 'password' => '123456']
        );
        $this->stampCustomers($r12->id);

        $r13=Customer::create(
            ['name' => 'tensor08', 'guid' => 'ISU2023070502061612', 'phone' => '973390350', 'password' => '123456']
        );
        $this->stampCustomers($r13->id);


    }

    private function stampCustomers($id){
       

        for($i=0;$i<1;$i++){
            $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-15 23:59:59', 'expired_at' => '2023-11-30 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-14 23:59:59', 'expired_at' => '2023-11-25 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-13 23:59:59', 'expired_at' => '2023-12-25 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-12 23:59:59', 'expired_at' => '2023-12-16 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-11 23:59:59', 'expired_at' => '2023-06-30 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]= ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-10 23:59:59', 'expired_at' => '2023-05-30 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => null];
            // $insertData[]=['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-09 23:59:59', 'expired_at' => '2023-09-22 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => '2023-03-01 23:59:59'];
            // $insertData[]= ['id'=>Str::uuid(),'customer_id' => $id, 'created_at' => '2023-05-08 23:59:59', 'expired_at' => '2023-09-23 23:59:59', 'source' => '系統發放', 'type' => 1, 'consumed_at' => '2023-03-01 23:59:59'];
        }

        StampCustomer::insert($insertData);
        $this->couponCustomers($id);
    }


    private function couponCustomers($id){
        $insertData=[
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>1,'coupon_cn_name'=>'生日禮劵','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-08-07 12:35:30','consumed_at'=>null,'coupon_id'=>'B20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>1,'coupon_cn_name'=>'好久不見','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-08-07 12:35:30','consumed_at'=>null,'coupon_id'=>'F20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'開卡禮','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','consumed_at'=>'2023-07-06 12:35:30','coupon_id'=>'C20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'會員大禮包','created_at'=>'2023-07-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','consumed_at'=>'2023-07-06 12:35:30','coupon_id'=>'M20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'開卡禮','created_at'=>'2023-05-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','consumed_at'=>'2023-07-06 12:35:30','coupon_id'=>'C20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
            ['id'=>Str::uuid(),'code_script'=>'ABCDEF1234567890','status'=>2,'coupon_cn_name'=>'會員大禮包','created_at'=>'2023-05-07 12:35:30','expired_at'=>'2023-07-07 12:35:30','consumed_at'=>'2023-06-07 12:35:30','coupon_id'=>'M20230707','customer_id'=>$id,'exchange_store'=>'Seven'],
        ];
        CouponCustomer::insert($insertData);
    }

}


