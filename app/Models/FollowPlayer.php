<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowPlayer extends Model
{
  use HasFactory;

  protected $fillable = [
    'store_uid', 'title', 'sub_title', 'copyright', 'artist', 'link_url',
    'area', 'content', 'review', 'operator', 'creator'
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
