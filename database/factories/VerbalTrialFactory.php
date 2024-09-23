<?php

namespace Database\Factories;

use App\Models\TypeOfCredit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerbalTrial>
 */
class VerbalTrialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // "committee_id" => $this->faker->unique()->numerify('CFNTG-###-##-##-##-#####'),
            // "committee_date" => $this->faker->date,
            // "civility" => $this->faker->randomElement(["Mr", "Mme", "Mlle"]),
            // "applicant_first_name" => $this->faker->firstName(),
            // "applicant_last_name" => $this->faker->lastName(),
            // "account_number" => $this->faker->unique()->numerify('############'),
            // "activity" => $this->faker->company(),
            // "purpose_of_financing" => $this->faker->company(),
            // "type_of_credit_id" => TypeOfCredit::inRandomOrder()->first()->id,
            // "amount" => $this->faker->randomFloat(0, 15000000, 150000000),
            // "duration" => $this->faker->randomFloat(0, 1, 120),
            // "periodicity" => $this->faker->randomElement(['mensual', 'quarterly', "semi-annual", "annual", 'in-fine']),
            // "taf" => 10,
            // "due_amount" => $this->faker->randomFloat(0, 150000, 1500000),
            // "administrative_fees_percentage" => $this->faker->randomFloat(0, 0, 100),
            // "insurance_premium" => $this->faker->randomFloat(0, 85000, 850000),
            // "tax_fee_interest_rate" => $this->faker->randomFloat(0, 0, 100),
            // "caf_id" => User::inRandomOrder()->where("profile", "caf")->first()->id ?? 1,
            // "creator_id" => User::where('profile', 'credit_analyst')->inRandomOrder()->first()->id ?? 1,
        ];
    }
}
