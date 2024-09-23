<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IndividualBusiness>
 */
class IndividualBusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // "contract_id" => Contract::inRandomOrder()->first()->id ?? Contract::factory(),
            // "denomination" => $this->faker->company(),
            // "corporate_purpose" => "pme",
            // "head_office_address" => "Lomé",
            // "rccm_number" => $this->faker->unique()->numerify("####"),
            // "nif" => $this->faker->unique()->numerify("####"),
            // "phone_number" => $this->faker->phoneNumber(),
            // "date_naiss" => $this->faker->date,
            // "date_delivrance" => $this->faker->date,
            // "lieux_naiss" =>$this->faker->city(),
            // "office_delivery" => $this->faker->name(),
            // "home_address" => $this->faker->address(),
            // "num_piece" => $this->faker->unique()->numerify("####"),
            // "first_name" =>  $this->faker->name(),
            // "last_name" => $this->faker->lastName(),
            // "nationalite" => $this->faker->country(),
            // "number_phone" => $this->faker->phoneNumber(),
            // "civility" =>$this->faker->randomElement(["Mr", "Mme", "Mlle"]),
            // "type_of_identity_document"=> $this->faker->randomElement(["cni", "passport", "residence_certificate", "driving_licence"]),
        ];
    }
}
