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
        'phone_cover_img',
        'created_at',
        'updated_at'
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
