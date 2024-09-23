<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guarantor>
 */
class GuarantorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // "contract_id" => Contract::inRandomOrder()->first()->id,
            // "civility" => $this->faker->randomElement(["Mr", "Mme", "Mlle"]),
            // "first_name" => $this->faker->firstName(),
            // "last_name" => $this->faker->lastName(),
            // "home_address" => $this->faker->address(),
            // "type_of_identity_document" => $this->faker->randomElement(["cni", "passport", "residence_certificate", "driving_licence"]),
            // "number_of_identity_document" => $this->faker->unique()->numerify("###-###-###"),
            // "date_of_issue_of_identity_document" => $this->faker->date,
            // "birth_date" => $this->faker->date,
            // "birth_place" => $this->faker->city(),
            // "nationality" => $this->faker->country(),
            // "function" => $this->faker->word(),
            // "phone_number" => $this->faker->phoneNumber(),
        ];
    }
}
