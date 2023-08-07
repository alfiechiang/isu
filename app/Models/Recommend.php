<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'open_start_time', 'open_end_time', 'name','precision','latitude',
        'phone','address','official_website','content','desc','image'
    ];
}
