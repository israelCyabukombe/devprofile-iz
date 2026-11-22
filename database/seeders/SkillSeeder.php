<?php

namespace Database\Seeders;

use App\Enums\SkillCategory;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\SkillLevel;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Seed the skills from BioWeb's src/data/Skills.ts, plus MySQL, MariaDB and Azure
     * from its last migration.
     */
    public function run(): void
    {
        $personalInfo = PersonalInfo::firstOrFail();

        foreach ($this->skills() as $skill) {
            $skillLevel = SkillLevel::where('description', $skill['level'])->firstOrFail();

            Skill::updateOrCreate(
                [
                    'personal_info_id' => $personalInfo->id,
                    'name' => $skill['name'],
                ],
                [
                    'skill_level_id' => $skillLevel->id,
                    'category' => $skill['category'],
                ],
            );
        }
    }

    /**
     * @return list<array{name: string, level: string, category: SkillCategory}>
     */
    private function skills(): array
    {
        return [
            ['name' => 'HTML', 'level' => 'Intermediate', 'category' => SkillCategory::FrontEnd],
            ['name' => 'CSS', 'level' => 'Beginner', 'category' => SkillCategory::FrontEnd],
            ['name' => 'Javascript', 'level' => 'Beginner', 'category' => SkillCategory::FrontEnd],
            ['name' => 'TypeScript', 'level' => 'Beginner', 'category' => SkillCategory::FrontEnd],

            ['name' => 'C#', 'level' => 'Beginner', 'category' => SkillCategory::BackEnd],
            ['name' => 'PHP', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],
            ['name' => 'SQL', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],
            ['name' => 'C++', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],
            ['name' => 'MySQL', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],
            ['name' => 'MariaDB', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],
            ['name' => 'MSSQL', 'level' => 'Intermediate', 'category' => SkillCategory::BackEnd],

            ['name' => 'React.js', 'level' => 'Beginner', 'category' => SkillCategory::Framework],
            ['name' => 'Laminas', 'level' => 'Beginner', 'category' => SkillCategory::Framework],
            ['name' => '.Net', 'level' => 'Beginner', 'category' => SkillCategory::Framework],

            ['name' => 'Git', 'level' => 'Beginner', 'category' => SkillCategory::Other],
            ['name' => 'Git Extensions', 'level' => 'Beginner', 'category' => SkillCategory::Other],
            ['name' => 'RabbitMQ', 'level' => 'Beginner', 'category' => SkillCategory::Other],
            ['name' => 'Elastic', 'level' => 'Beginner', 'category' => SkillCategory::Other],
            ['name' => 'Jenkins', 'level' => 'Beginner', 'category' => SkillCategory::Other],
            ['name' => 'Azure', 'level' => 'Intermediate', 'category' => SkillCategory::Other],
        ];
    }
}
