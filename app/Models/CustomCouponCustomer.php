<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasOne;


class CustomCouponCustomer extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = [
       'guid', 'coupon_code', 'disable', 'coupon_name', 'customer_name','phone' ,'email',
        'exchange_tome','exchange_place','exchanger','exchange','desc'
    ];

    public function coupon(): HasOne
    {
        return $this->hasOne(CustomCoupon::class,'code','coupon_code');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'guid','guid');
    }

}


