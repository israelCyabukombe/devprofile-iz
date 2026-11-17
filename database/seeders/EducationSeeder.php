<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\PersonalInfo;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Seed the Education section, copied from BioWeb's About.tsx.
     */
    public function run(): void
    {
        $personalInfo = PersonalInfo::firstOrFail();

        Education::updateOrCreate(
            [
                'personal_info_id' => $personalInfo->id,
                'degree' => 'B.Sc. in Computer Science',
            ],
            [
                'school' => 'South Dakota State University (SDSU)',
                'minor' => 'Mathematics',
                'sort_order' => 1,
            ],
        );
    }
}
