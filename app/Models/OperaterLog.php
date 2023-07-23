<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperaterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_email','email', 'area', 'column', 'type','created_at'
    ];

    const UPDATED_AT = null;

}
