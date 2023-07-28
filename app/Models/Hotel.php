<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'phone',
        'hotel_url',
        'hotel_desc',
        'district',
        'country',
        'county',
        'google_map_url',
        'address',
        'desc'
    ];

}
