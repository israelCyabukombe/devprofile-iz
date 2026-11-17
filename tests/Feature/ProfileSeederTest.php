<?php

use App\Enums\SkillCategory;
use App\Models\Education;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillLevel;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\EducationSeeder;
use Database\Seeders\PersonalInfoSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\SkillLevelSeeder;
use Database\Seeders\SkillSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('seeds the profile from BioWeb', function () {
    $personalInfo = PersonalInfo::sole();

    expect($personalInfo->fullName())->toBe('Israel Cyabukombe')
        ->and($personalInfo->job_title)->toBe('Software Engineer');
});

test('seeds the education', function () {
    $education = Education::sole();

    expect($education->degree)->toBe('B.Sc. in Computer Science')
        ->and($education->minor)->toBe('Mathematics');
});

test('seeds the three skill levels', function () {
    expect(SkillLevel::count())->toBe(3);
});

test('seeds every skill in its category', function () {
    $phpSkill = Skill::where('name', 'PHP')->sole();

    expect(Skill::count())->toBe(19)
        ->and($phpSkill->category)->toBe(SkillCategory::BackEnd);
});

test('seeds placeholder projects with their skills', function () {
    $firstProject = Project::where('sort_order', 1)->sole();

    expect(Project::count())->toBe(2)
        ->and($firstProject->skills->pluck('name')->sort()->values()->all())->toBe(['HTML', 'MySQL', 'PHP']);
});

test('running the profile seeders twice does not create duplicates', function () {
    $this->seed([
        SkillLevelSeeder::class,
        PersonalInfoSeeder::class,
        EducationSeeder::class,
        SkillSeeder::class,
        ProjectSeeder::class,
    ]);

    expect(PersonalInfo::count())->toBe(1)
        ->and(Skill::count())->toBe(19)
        ->and(Project::count())->toBe(2);
});
