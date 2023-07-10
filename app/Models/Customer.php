<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Customer
 *
 * @property string $id 會員ID
 * @property \Illuminate\Support\Carbon|null $created_at 建立時間
 * @property \Illuminate\Support\Carbon|null $updated_at 更新時間
 * @property \Illuminate\Support\Carbon|null $deleted_at 刪除時間
 * @property string|null $name 姓名
 * @property string|null $email Email
 * @property string|null $phone 手機
 * @property string $password 密碼
 * @property string|null $avatar 頭像
 * @property string|null $status 狀態
 * @property string|null $gender 性別
 * @property string|null $birthday 生日
 * @property string|null $address 地址
 * @property string|null $interest 興趣
 * @property string|null $citizenship 國籍
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCitizenship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withoutTrashed()
 * @mixin \Eloquent
 */
class Customer extends Authenticatable implements JWTSubject
{
    protected string $guard = "customers";

    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'citizenship', 'avatar', 'status', 'gender', 'birthday', 'address', 'interest', 'point_balance', 'stamps', 'legacy_system_id', 'created_at', 'updated_at',
        'country','county','district','postal','join_group','guid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'point_balance' => 'int',
        'stamps' => 'int',
        'interest' => 'json',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    protected function avatarFullUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Arr::get($attributes, 'avatar') ? Storage::url(Arr::get($attributes, 'avatar')) : null,
        );
    }

    public function stampCustomer(): HasMany
    {
        return $this->hasMany(StampCustomer::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(PointCustomer::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(CouponCustomer::class);
    }

    public function logins(): HasMany
    {
        return $this->hasMany(LoginCustomer::class);
    }

    public function social_accounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }
}
