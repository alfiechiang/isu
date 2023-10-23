<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCouponPeopleList extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code','coupon_name','guid', 'phone', 'email','created_at'
    ];
}



