<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'type',
        'content',
        'operator',
        'web_cover_img',
        'phone_cover_img'
    ];

    public function getCreatedAtAttribute($value)
    {
      return Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }
  
    public function getUpdatedAtAttribute($value)
    {
      return Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }



}
