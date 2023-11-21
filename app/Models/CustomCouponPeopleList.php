<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomCouponPeopleList extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code','coupon_name','guid', 'phone', 'email','created_at'
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class,'guid','guid');
    }
}



