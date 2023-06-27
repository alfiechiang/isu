<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponCustomer>
 */
class CouponCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $coupon_code = CouponCode::factory()->create();

        return [
            'expired_at' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['unused', 'used']),
            'memo' => fake()->sentence(3),
            'coupon_id' => $coupon_code->coupon_id,
            'coupon_code_id' => $coupon_code->id,
            'customer_id' => Customer::factory(),
        ];
    }
}
