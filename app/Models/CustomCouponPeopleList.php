<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomCouponPeopleList extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code','coupon_name','guid', 'phone', 'email','created_at'
    ];

    public function customers(): HasOne
    {
        return $this->hasOne(Customer::class,'guid','guid');
    }
}



