<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use App\Models\Customer;
use App\Point\PointEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointCustomer>
 */
class PointCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expired_at' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'source' => fake()->randomElement([
                PointEnums::SOURCE_REDEEM_STAMP,
                PointEnums::SOURCE_SCAN_STORE,
                PointEnums::SOURCE_CONSUMPTION_INVOICE,
                PointEnums::SOURCE_CONSUMPTION_INPUT,
            ]),
            'memo' => fake()->sentence(3),
            'point_balance' => fake()->numberBetween(0, 10000),
            'value' => fake()->numberBetween(1, 100),
            'is_redeem' => fake()->boolean(1),
            'customer_id' => Customer::factory(),
        ];
    }
}
