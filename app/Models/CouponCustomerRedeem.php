<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CouponCustomerRedeem extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'memo', 'coupon_customer_id',
        'operator_type', 'operator_id', 'reference_type', 'reference_id',
    ];

    const UPDATED_AT = null;

    public function coupon_customer(): BelongsTo
    {
        return $this->belongsTo(CouponCustomer::class);
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
