<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Otp
 *
 * @property string $id OTP ID
 * @property \Illuminate\Support\Carbon|null $created_at 建立時間
 * @property \Illuminate\Support\Carbon|null $updated_at 更新時間
 * @property string $identifier 綁定到 OTP 的身份
 * @property string $token OTP Token
 * @property int $valid OTP 是否可用
 * @property string|null $expired_at 過期時間
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Otp whereValid($value)
 * @mixin \Eloquent
 */
class Otp extends Model
{
    use HasFactory, HasUuids, Notifiable;

    const SEND_INTERVAL = 600;
    const SEND_LIMIT = 5;

    const AUTH_CODE_TTL = 600;

    protected $country_code = 'sssss';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identifier', 'token', 'expired_at', 'valid', 'country_code',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function routeNotificationForSms(): string
    {
        return $this->identifier;
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }

    public function routeNotificationForMail(): string
    {
        return $this->identifier;
    }
}
