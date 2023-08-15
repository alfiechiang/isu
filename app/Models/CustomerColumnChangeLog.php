<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerColumnChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id','table_name','column_name'
      ];

}
