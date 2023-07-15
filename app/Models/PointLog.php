<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'type', 'created_at', 'points_num','operator','desc'
    ];
    const UPDATED_AT = null;

}
