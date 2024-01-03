<?php

namespace App\Models;

use App\Stamp\Enums\StampDistribution;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StampCustomer extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id', 'type', 'expired_at', 'memo','source',
        'operator_type', 'operator_id', 'reference_type', 'reference_id',
        'remain_quantity', 'value', 'is_redeem', 'store_id', 'consumed_at','created_at',
    ];

    const UPDATED_AT = null;

    protected $casts = [
        'consumed_at' => 'datetime:Y-m-d H:i:s',
        'expired_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo('reference', 'reference_type', 'reference_id');
    }

    public function operator(): MorphTo
    {
        return $this->morphTo('operator', 'operator_type', 'operator_id');
    }

    public function presentguid(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'guid','source');
    }
}
