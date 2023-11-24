<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'mode' => fake()->randomElement([CouponEnums::MODE_DRAW, CouponEnums::MODE_SINGLE, CouponEnums::MODE_MULTI,]),
            'type' => fake()->randomElement([CouponEnums::TYPE_SPECIAL, CouponEnums::TYPE_SLEEPING, CouponEnums::TYPE_BIRTHDAY, CouponEnums::TYPE_INFORMATION_COMPLETE, CouponEnums::TYPE_OPEN_CARD,]),
            'validity' => fake()->numberBetween(60, 1440),
            'start_at' => fake()->dateTimeBetween('now', '+1 week'),
            'end_at' => fake()->dateTimeBetween('+1 week', '+2 weeks')
        ];
    }
}
