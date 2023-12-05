<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'img', 'per_people_volume', 'total_issue','issue_time' ,'expire_time',
        'coupon_desc','notice_desc','notify','shelve','operator','operator_ip'
    ];

}
