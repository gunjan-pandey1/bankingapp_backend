<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LmsTransaction>
 */
class LmsTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->uuid,
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'transaction_date' => $this->faker->dateTimeThisYear,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'description' => $this->faker->sentence,
        ];
    }
}
