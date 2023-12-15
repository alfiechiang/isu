<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;
    protected $fillable = [
       'id', 'store_uid', 'exchange_num', 'spend_stamp_num', 'stock','item_name','desc'
    ];
}
