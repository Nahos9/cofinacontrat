<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VerbalTrial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // "verbal_trial_id" => VerbalTrial::inRandomOrder()->first()->id,
            // "representative_birth_date" => $this->faker->date,
            // "representative_birth_place" => $this->faker->city(),
            // "representative_nationality" => $this->faker->country,
            // "representative_home_address" => $this->faker->address(),
            // "representative_phone_number" => $this->faker->phoneNumber(),
            // "representative_type_of_identity_document" => $this->faker->randomElement(["cni", "passport", "residence_certificate", "driving_licence"]),
            // "representative_number_of_identity_document" => $this->faker->unique()->numerify('###-###-###'),
            // "representative_date_of_issue_of_identity_document" => $this->faker->date,
            // "risk_premium_percentage" => $this->faker->numberBetween(10, 30),
            // "total_amount_of_interest" => 14785236,
            // "number_of_due_dates" => $this->faker->numberBetween(1, 25),
            // "type" => $this->faker->randomElement(['particular', 'company', 'individual_business']),
            // "creator_id" => User::where('profile', 'credit_admin')->inRandomOrder()->first()->id ?? 1,
        ];
    }
}
