<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
      'store_uid','title','sub_title','copyright','artist','link_url',
      'area','content','review','operator','creator','updated_at','created_at'
    ];

    public function getCreatedAtAttribute($value)
    {
        $dateTime = Carbon::parse($value, 'UTC');
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        $dateTime = Carbon::parse($value, 'UTC');
        return $dateTime->format('Y-m-d H:i:s');
    }

   

}
