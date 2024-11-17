<?php

namespace Database\Factories;

use App\Models\LmsBankDetails;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LmsBankDetails>
 */
class LmsBankDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_number' => $this->faker->bankAccountNumber,
            'bank_name' => $this->faker->bank,
            'ifsc_code' => $this->faker->regexify('[A-Z]{4}0[A-Z0-9]{6}'),
            'branch_name' => $this->faker->city,
        ];
    }
}

LmsBankDetails::factory()->count(50)->create();
