<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\PersonalInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'personal_info_id' => PersonalInfo::factory(),
            'degree' => 'B.Sc. in '.fake()->word(),
            'school' => fake()->company().' University',
            'minor' => null,
            'sort_order' => 0,
        ];
    }
}
