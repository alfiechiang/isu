<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointCustomer extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expired_at', 'point_balance', 'memo', 'value', 'is_redeem', 'customer_id', 'source',
        'operator_type', 'operator_id', 'reference_type', 'reference_id',
    ];

    const UPDATED_AT = null;

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    public function operator(): MorphTo
    {
        return $this->morphTo('operator', 'operator_type', 'operator_id');
    }
}
