<?php

namespace Database\Factories;

use App\Enums\SkillCategory;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\SkillLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
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
            'skill_level_id' => SkillLevel::factory(),
            'name' => fake()->unique()->word(),
            'category' => fake()->randomElement(SkillCategory::cases()),
        ];
    }

    /**
     * Put the skill in the given category.
     */
    public function inCategory(SkillCategory $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}
