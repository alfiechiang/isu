<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePrivilegeRoleLog extends Model
{
    use HasFactory;

    protected $table = 'store_privilege_role_logs';

    protected $fillable = [
        'uid', 'access_token',
    ];
}
