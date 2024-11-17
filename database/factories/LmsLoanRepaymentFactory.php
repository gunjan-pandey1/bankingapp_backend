<?php

namespace Database\Factories;

use App\Models\LmsLoanRepayment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LmsLoanRepayment>
 */
class LmsLoanRepaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_id' => $this->faker->randomDigitNotNull,
            'amount_paid' => $this->faker->randomFloat(2, 100, 5000),
            'payment_date' => $this->faker->dateTimeThisMonth,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'remarks' => $this->faker->sentence,
        ];
    }
}
