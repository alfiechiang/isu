<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponDisableLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'guid', 'coupon_code', 'coupon_name', 'operator', 'disable_time','desc' ,'operator_ip'
    ];

}
