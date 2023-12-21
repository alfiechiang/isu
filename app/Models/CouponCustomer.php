<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CouponCustomer extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expired_at', 'status', 'memo', 'coupon_id', 'coupon_cn_name','coupon_script' ,'customer_id',
        'operator_type', 'operator_id', 'reference_type', 'reference_id','disable'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    const UPDATED_AT = null;

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

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

    public function coupon_code(): BelongsTo
    {
        return $this->belongsTo(CouponCode::class);
    }

    public function redeem(): HasOne
    {
        return $this->hasOne(CouponCustomerRedeem::class);
    }
}
