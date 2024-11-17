<?php

namespace Database\Factories;

use App\Models\LmsLoan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LmsLoan>
 */
class LmsLoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_amount' => $this->faker->numberBetween(1000, 100000),
            'interest_rate' => $this->faker->randomFloat(2, 1, 15),
            'loan_term' => $this->faker->numberBetween(1, 30),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
        ];
    }
}

LmsLoan::factory()->count(50)->create();
