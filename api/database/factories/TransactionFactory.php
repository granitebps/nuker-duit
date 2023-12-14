<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(1000, 10000);
        return [
            'currency_id' => Currency::factory(),
            'side' => fake()->randomElement(['buy', 'sell']),
            'amount' => $amount,
            'rate' => 15000,
            'total' => $amount * 15000
        ];
    }
}
