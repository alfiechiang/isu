<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OperaterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_email','email', 'area', 'column', 'type','created_at','guid'
    ];

    const UPDATED_AT = null;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'guid','guid');
    }

}
