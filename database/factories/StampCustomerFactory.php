<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use App\Models\Customer;
use App\Models\Store;
use App\Stamp\Enums\StampDistribution;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StampCustomer>
 */
class StampCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'customer_id' => Customer::factory(),
            'type' => fake()->randomElement([StampDistribution::POINT->value, StampDistribution::STORE->value,]),
            'expired_at' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'remain_quantity' => fake()->numberBetween(0, 10000),
            'value' => fake()->numberBetween(1, 100),
            'is_redeem' => fake()->boolean(1),
            'memo' => fake()->optional(0.3, null)->text(50),
            'store_id' => Store::factory(),
        ];
    }
}
