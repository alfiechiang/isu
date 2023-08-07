<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
      'store_uid','title','sub_title','copyright','artist','link_url',
      'area','content','review'
    ];
}
