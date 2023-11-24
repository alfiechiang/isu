<?php

namespace Database\Factories;

use App\Coupon\CouponEnums;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => fake()->imageUrl(640, 480, 'cats'),
            'number' => fake()->regexify('\d{10}'),
            'amount' => fake()->numberBetween(100, 10000),
            'purchased_at' => fake()->date("Y-m-d", 'now'),
        ];
    }
}
