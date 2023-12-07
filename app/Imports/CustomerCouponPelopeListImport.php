<?php

namespace App\Imports;

use App\Models\CustomCouponPeopleList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerCouponPelopeListImport implements ToCollection
{

    protected $coupon_code;

    protected $coupon_name;


    public function __construct($coupon_code,$coupon_name)
    {
        $this->coupon_code = $coupon_code;
        $this->coupon_name = $coupon_name;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        unset($rows[0]);
        DB::transaction(function () use ($rows) {
            CustomCouponPeopleList::where('coupon_code',$this->coupon_code)->delete();

            foreach ($rows as $row) {
                if(!is_null($row[0])){
                    CustomCouponPeopleList::create([
                        'coupon_code' => $this->coupon_code,
                        'coupon_name'=>$this->coupon_name,
                        'guid' => $row[0],
                        'phone' => $row[1],
                        'email' => $row[2]
                    ]);
                }
               
            }
        });
    }
}
