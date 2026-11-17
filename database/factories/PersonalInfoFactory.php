<?php

namespace Database\Factories;

use App\Models\PersonalInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PersonalInfo>
 */
class PersonalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'job_title' => fake()->jobTitle(),
            'summary_text' => fake()->paragraph(),
            'profile_photo_path' => null,
            'resume_path' => null,
            'github_url' => fake()->url(),
            'linkedin_url' => fake()->url(),
        ];
    }
}
