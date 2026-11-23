<?php

namespace App\Http\Controllers;

use App\Enums\SkillCategory;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const string FALLBACK_TITLE = 'Israel Cyabu';

    private const string FALLBACK_DESCRIPTION = 'Career profile, skills and project samples.';

    /**
     * Search engines show about 155–160 characters of a description.
     */
    private const int META_DESCRIPTION_LENGTH = 160;

    /**
     * Show the career / profile home page.
     */
    public function index(): View
    {
        $personalInfo = $this->findProfile();
        $skillGroups = $this->groupSkillsByCategory($personalInfo);
        $projects = $this->findProjects();
        $seo = $this->buildSeoTags($personalInfo);

        return view('home.index', [
            'personalInfo' => $personalInfo,
            'skillGroups' => $skillGroups,
            'projects' => $projects,
            'seo' => $seo,
        ]);
    }

    /**
     * Values for the page title, meta description, Open Graph tags and JSON-LD.
     * Falls back to generic text while the database is not seeded yet.
     *
     * @return array{title: string, description: string, canonicalUrl: string, imageUrl: string|null, imageAlt: string|null, structuredData: array<string, mixed>|null}
     */
    private function buildSeoTags(?PersonalInfo $personalInfo): array
    {
        $canonicalUrl = route('home');

        if ($personalInfo === null) {
            return [
                'title' => self::FALLBACK_TITLE,
                'description' => self::FALLBACK_DESCRIPTION,
                'canonicalUrl' => $canonicalUrl,
                'imageUrl' => null,
                'imageAlt' => null,
                'structuredData' => null,
            ];
        }

        $imageUrl = $this->findProfilePhotoUrl($personalInfo);

        return [
            'title' => $personalInfo->fullName().' — '.$personalInfo->job_title,
            'description' => $this->buildMetaDescription($personalInfo),
            'canonicalUrl' => $canonicalUrl,
            'imageUrl' => $imageUrl,
            'imageAlt' => 'Portrait of '.$personalInfo->fullName(),
            'structuredData' => $this->buildPersonStructuredData($personalInfo, $canonicalUrl, $imageUrl),
        ];
    }

    /**
     * The summary on one line, cut at a word boundary so search results don't truncate it mid-word.
     */
    private function buildMetaDescription(PersonalInfo $personalInfo): string
    {
        $summaryOnOneLine = Str::squish($personalInfo->summary_text);

        return Str::limit($summaryOnOneLine, self::META_DESCRIPTION_LENGTH, '...', preserveWords: true);
    }

    /**
     * Absolute URL of the profile photo, or null when there is none.
     * Link previews (LinkedIn, Slack) need an absolute URL.
     */
    private function findProfilePhotoUrl(PersonalInfo $personalInfo): ?string
    {
        if ($personalInfo->profile_photo_path === null) {
            return null;
        }

        return asset($personalInfo->profile_photo_path);
    }

    /**
     * schema.org Person data, so search engines can tell this page is about one person.
     *
     * @return array<string, mixed>
     */
    private function buildPersonStructuredData(PersonalInfo $personalInfo, string $canonicalUrl, ?string $imageUrl): array
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $personalInfo->fullName(),
            'jobTitle' => $personalInfo->job_title,
            'url' => $canonicalUrl,
        ];

        if ($imageUrl !== null) {
            $structuredData['image'] = $imageUrl;
        }

        $profileLinks = $this->findProfileLinks($personalInfo);

        if (count($profileLinks) > 0) {
            $structuredData['sameAs'] = $profileLinks;
        }

        return $structuredData;
    }

    /**
     * The GitHub and LinkedIn URLs that are set.
     *
     * @return list<string>
     */
    private function findProfileLinks(PersonalInfo $personalInfo): array
    {
        $profileLinks = [];

        if ($personalInfo->github_url !== null) {
            $profileLinks[] = $personalInfo->github_url;
        }

        if ($personalInfo->linkedin_url !== null) {
            $profileLinks[] = $personalInfo->linkedin_url;
        }

        return $profileLinks;
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
