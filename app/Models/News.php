<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
