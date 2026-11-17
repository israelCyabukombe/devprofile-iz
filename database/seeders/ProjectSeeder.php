<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed PLACEHOLDER projects. Replace these with the real projects before Phase 4.
     */
    public function run(): void
    {
        foreach ($this->placeholderProjects() as $projectData) {
            $project = Project::updateOrCreate(
                ['title' => $projectData['title']],
                [
                    'description' => $projectData['description'],
                    'live_url' => $projectData['live_url'],
                    'repo_url' => $projectData['repo_url'],
                    'sort_order' => $projectData['sort_order'],
                ],
            );

            $skillIds = Skill::whereIn('name', $projectData['skill_names'])->pluck('id');

            $project->skills()->sync($skillIds);
        }
    }

    /**
     * @return list<array{title: string, description: string, live_url: string|null, repo_url: string|null, sort_order: int, skill_names: list<string>}>
     */
    private function placeholderProjects(): array
    {
        return [
            [
                'title' => 'Placeholder Project One',
                'description' => 'Placeholder description. Replace this project with real data before Phase 4.',
                'live_url' => 'https://example.com',
                'repo_url' => 'https://github.com/example/placeholder-one',
                'sort_order' => 1,
                'skill_names' => ['PHP', 'MySQL', 'HTML'],
            ],
            [
                'title' => 'Placeholder Project Two',
                'description' => 'Placeholder description. Replace this project with real data before Phase 4.',
                'live_url' => null,
                'repo_url' => 'https://github.com/example/placeholder-two',
                'sort_order' => 2,
                'skill_names' => ['C#', '.Net', 'React.js'],
            ],
        ];
    }
}
