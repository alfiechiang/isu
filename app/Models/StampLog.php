<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StampLog extends Model
{
    protected $fillable = [
        'customer_id', 'type', 'created_at', 'expired_at','operator','desc',
    ];
    const UPDATED_AT = null;

}
