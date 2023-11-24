<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '09' . fake()->numberBetween(10000000, 99999999),
            'password' => bcrypt('password'),
            'citizenship' => fake()->randomElement(['foreign', 'native']),
            'avatar' => fake()->imageUrl(640, 480, 'people'),
            'status' => fake()->randomElement(['disabled', 'enabled', 'unverified']),
            'gender' => fake()->randomElement(['male', 'female']),
            'birthday' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'address' => fake()->address(),
            'interest' => fake()->randomElement(['sports', 'music', 'food', 'travel']),
            'point_balance' => fake()->numberBetween(0, 1000),
        ];
    }
}
