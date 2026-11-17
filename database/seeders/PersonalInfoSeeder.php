<?php

namespace Database\Seeders;

use App\Models\PersonalInfo;
use Illuminate\Database\Seeder;

class PersonalInfoSeeder extends Seeder
{
    /**
     * Seed the profile, copied from BioWeb's src/data/PersonalInfo.ts.
     */
    public function run(): void
    {
        $summaryText = 'Full‑stack software engineer with 8 years of experience building enterprise healthcare '
            .'applications in PHP (Laminas/Mezzio) and .NET, with expertise in senior care operations, document '
            .'management, and designing automated reports that help stakeholders understand and act on their data.';

        PersonalInfo::updateOrCreate(
            ['email' => 'israel.cyabu93@gmail.com'],
            [
                'first_name' => 'Israel',
                'last_name' => 'Cyabukombe',
                'job_title' => 'Software Engineer',
                'summary_text' => $summaryText,
                'profile_photo_path' => 'images/IMG_0002.jpg',
                'resume_path' => 'ISRAEL CYABUKOMBE RESUME.pdf',
                'github_url' => 'https://github.com/israelCyabukombe',
                'linkedin_url' => 'https://www.linkedin.com/in/israel-cyabukombe-974ba2aa/',
            ],
        );
    }
}
