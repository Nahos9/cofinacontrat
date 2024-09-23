<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CAT>
 */
class CATFactory extends Factory
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
            // "credit_number" => $this->faker->numerify("########"),
            // "sector" => $this->faker->company(),
            // "first_deadline" => $this->faker->date(),
            // "last_deadline" => $this->faker->date(),
            // "source_of_reimbursement" => $this->faker->randomElement(["revenue_from_the_activity", "final_payer_settlement", "resale_of_goods"]),
            // "instructions_from_the_risk_and_credit_department" => "Restriction sur le compte sous reserve du retrait du tableau d’amortissement et du contrat",
            // "outstanding_number_ready_to_settle" => $this->faker->numerify("########"),
            // "other_expenses" => $this->faker->randomFloat(0, 15000, 150000),
            // "teg" => $this->faker->randomFloat(0, 1500000, 15000000),
            // "validation_status" => "waiting",
            // "unblock_status" => "waiting",
        ];
    }
}
