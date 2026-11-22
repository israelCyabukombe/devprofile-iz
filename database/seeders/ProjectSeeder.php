<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use RuntimeException;

class ProjectSeeder extends Seeder
{
    /**
     * Seed the real projects, copied from the live BioWeb site (icyabu.com).
     * BioWeb only ever had them in its Azure database, so this seeder is now their source.
     * Run SkillSeeder first: each project's tech stack is linked to existing skills by name.
     */
    public function run(): void
    {
        $this->removePlaceholderProjects();

        foreach ($this->projects() as $projectData) {
            $project = Project::updateOrCreate(
                ['title' => $projectData['title']],
                [
                    'description' => $projectData['description'],
                    'live_url' => $projectData['live_url'],
                    'repo_url' => $projectData['repo_url'],
                    'sort_order' => $projectData['sort_order'],
                ],
            );

            $skillIds = $this->findSkillIds($projectData['skill_names'], $projectData['title']);

            $project->skills()->sync($skillIds);
        }
    }

    /**
     * Earlier versions of this seeder added two placeholder projects. Delete them from
     * databases that still have them (their tech stack rows are deleted with them).
     */
    private function removePlaceholderProjects(): void
    {
        Project::whereIn('title', ['Placeholder Project One', 'Placeholder Project Two'])->delete();
    }

    /**
     * Look up the skill ids for a project's tech stack. Stops with an error if a name
     * doesn't match a seeded skill, so a typo can't silently drop a skill.
     *
     * @param  list<string>  $skillNames
     * @return list<int>
     */
    private function findSkillIds(array $skillNames, string $projectTitle): array
    {
        $skillIdsByName = Skill::whereIn('name', $skillNames)->pluck('id', 'name');

        $missingSkillNames = [];

        foreach ($skillNames as $skillName) {
            if (! $skillIdsByName->has($skillName)) {
                $missingSkillNames[] = $skillName;
            }
        }

        if (count($missingSkillNames) > 0) {
            throw new RuntimeException(
                'Project "'.$projectTitle.'" uses skills that are not seeded: '.implode(', ', $missingSkillNames)
                .'. Add them to SkillSeeder or fix the spelling.'
            );
        }

        return $skillIdsByName->values()->all();
    }

    /**
     * @return list<array{title: string, description: string, live_url: string|null, repo_url: string|null, sort_order: int, skill_names: list<string>}>
     */
    private function projects(): array
    {
        return [
            [
                'title' => 'DocSearch',
                'description' => '.NET Minimal Web API acting as a middle layer for retrieving resident/patient documents from OnBase, '
                    .'a healthcare document management platform. Later hosted by Hyland after direct DB access was required '
                    .'to improve performance. Paired with a Mezzio PHP application providing document search, listing, '
                    .'viewing, downloading and more.',
                'live_url' => null,
                'repo_url' => null,
                'sort_order' => 1,
                'skill_names' => [
                    'C#', 'PHP', 'SQL', 'HTML', 'CSS', 'Javascript', 'RabbitMQ',
                    'Laminas', '.Net', 'MySQL', 'MariaDB', 'Azure',
                ],
            ],
            [
                'title' => 'QualityTracking',
                'description' => 'Laminas(PHP-based) Quality Tracking application that enabled the company to create, schedule, '
                    .'and assign audits to specific locations to quantify the level of care residents receive, including '
                    .'reports for tracking audit outcomes.',
                'live_url' => null,
                'repo_url' => null,
                'sort_order' => 2,
                'skill_names' => [
                    'PHP', 'SQL', 'HTML', 'CSS', 'Javascript', 'RabbitMQ',
                    'Laminas', '.Net', 'MySQL', 'MariaDB', 'Azure',
                ],
            ],
            [
                'title' => 'My Bio Web',
                'description' => 'The project you are currently viewing showcases my portfolio and career résumé, including '
                    .'technical skills and projects. It is built with a React TypeScript frontend and a .NET Core Minimal Web '
                    .'API backend with SQL Server, using RESTful API design, OOP, and CRUD operations. I used this project to '
                    .'gain hands-on experience with React and TypeScript, using AI-assisted development as a key learning '
                    .'resource and support for implementation',
                'live_url' => 'https://www.icyabu.com/',
                'repo_url' => 'https://github.com/israelCyabukombe/BioWeb',
                'sort_order' => 3,
                'skill_names' => ['C#', 'React.js', 'CSS', 'TypeScript', '.Net', 'Azure', 'MSSQL'],
            ],
        ];
    }
}
