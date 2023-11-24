<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponCode>
 */
class CouponCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => function () {
                $prefix = fake()->randomElement(['AAA', 'AAB']);
                $uuid = Uuid::uuid4();
                $suffix = fake()->date('Ymd');

                return "{$prefix}-{$uuid->toString()}-{$suffix}";
            },
            'coupon_id' => Coupon::factory(),
        ];
    }
}
