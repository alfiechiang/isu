<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'phone',
        'house_phone',
        'hotel_url',
        'hotel_desc',
        'district',
        'country',
        'county',
        'google_map_url',
        'address',
        'desc'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class,'hotel_id','id');
    }

}
