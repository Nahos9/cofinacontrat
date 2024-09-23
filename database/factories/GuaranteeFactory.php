<?php

namespace Database\Factories;

use App\Models\TypeOfGuarantee;
use App\Models\VerbalTrial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guarantee>
 */
class GuaranteeFactory extends Factory
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
            // "type_of_guarantee_id" => TypeOfGuarantee::where('id', '!=', 9)->inRandomOrder()->first()->id,
            // "comment" => $this->faker->sentence(15),
        ];
    }
}
