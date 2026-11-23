<?php

namespace App\Http\Controllers;

use App\Enums\SkillCategory;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the career / profile home page.
     */
    public function index(): View
    {
        $personalInfo = $this->findProfile();
        $skillGroups = $this->groupSkillsByCategory($personalInfo);
        $projects = $this->findProjects();

        return view('home.index', [
            'personalInfo' => $personalInfo,
            'skillGroups' => $skillGroups,
            'projects' => $projects,
        ]);
    }

    /**
     * The site shows one profile. It is null until the database has been seeded.
     */
    private function findProfile(): ?PersonalInfo
    {
        return PersonalInfo::query()
            ->with(['education', 'skills'])
            ->first();
    }

    /**
     * Group the profile's skills under their category headings, in the order the
     * categories are listed in the SkillCategory enum. Empty categories are left out.
     *
     * @return list<array{label: string, skills: list<Skill>}>
     */
    private function groupSkillsByCategory(?PersonalInfo $personalInfo): array
    {
        if ($personalInfo === null) {
            return [];
        }

        $skillGroups = [];

        foreach (SkillCategory::cases() as $category) {
            $skillsInCategory = $this->findSkillsInCategory($personalInfo->skills, $category);

            if (count($skillsInCategory) === 0) {
                continue;
            }

            $skillGroups[] = [
                'label' => $category->label(),
                'skills' => $skillsInCategory,
            ];
        }

        return $skillGroups;
    }

    /**
     * Pick out the skills that belong to one category, keeping their order.
     *
     * @param  Collection<int, Skill>  $skills
     * @return list<Skill>
     */
    private function findSkillsInCategory(Collection $skills, SkillCategory $category): array
    {
        $skillsInCategory = [];

        foreach ($skills as $skill) {
            if ($skill->category === $category) {
                $skillsInCategory[] = $skill;
            }
        }

        return $skillsInCategory;
    }

    /**
     * All projects with their tech stack, highest sort_order first.
     *
     * @return Collection<int, Project>
     */
    private function findProjects(): Collection
    {
        return Project::query()
            ->with('skills')
            ->orderByDesc('sort_order')
            ->get();
    }
}
