<?php

namespace Database\Seeders;

use App\Models\SkillLevel;
use Illuminate\Database\Seeder;

class SkillLevelSeeder extends Seeder
{
    /**
     * Seed the three skill levels used by BioWeb.
     */
    public function run(): void
    {
        $levelDescriptions = ['Beginner', 'Intermediate', 'Advanced'];

        foreach ($levelDescriptions as $description) {
            SkillLevel::firstOrCreate(['description' => $description]);
        }
    }
}
