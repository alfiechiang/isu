<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePrivilegeRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'cn_name',
    ];
}
