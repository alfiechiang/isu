<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\StampCustomer;
use App\Stamp\Enums\StampDistribution;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponCustomerRedeem>
 */
class CouponCustomerRedeemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coupon_customer_id' => CouponCustomer::factory(),
            'memo' => fake()->optional(0.3, null)->text(50),
        ];
    }
}
