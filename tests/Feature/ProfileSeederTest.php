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
    $mssqlSkill = Skill::where('name', 'MSSQL')->sole();

    expect(Skill::count())->toBe(20)
        ->and($phpSkill->category)->toBe(SkillCategory::BackEnd)
        ->and($mssqlSkill->category)->toBe(SkillCategory::BackEnd)
        ->and($mssqlSkill->skillLevel->description)->toBe('Intermediate');
});

test('seeds the real projects in order', function () {
    $titlesInOrder = Project::orderBy('sort_order')->pluck('title')->all();

    expect($titlesInOrder)->toBe(['DocSearch', 'QualityTracking', 'My Bio Web']);
});

test('seeds each project with its full tech stack', function () {
    $docSearch = Project::where('title', 'DocSearch')->sole();
    $qualityTracking = Project::where('title', 'QualityTracking')->sole();
    $myBioWeb = Project::where('title', 'My Bio Web')->sole();

    expect($docSearch->skills)->toHaveCount(12)
        ->and($qualityTracking->skills)->toHaveCount(11)
        ->and($myBioWeb->skills->pluck('name')->sort()->values()->all())
        ->toBe(['.Net', 'Azure', 'C#', 'CSS', 'MSSQL', 'React.js', 'TypeScript']);
});

test('only My Bio Web has repo and live links', function () {
    $myBioWeb = Project::where('title', 'My Bio Web')->sole();
    $docSearch = Project::where('title', 'DocSearch')->sole();

    expect($myBioWeb->repo_url)->toBe('https://github.com/israelCyabukombe/BioWeb')
        ->and($myBioWeb->live_url)->toBe('https://www.icyabu.com/')
        ->and($docSearch->repo_url)->toBeNull()
        ->and($docSearch->live_url)->toBeNull();
});

test('removes the old placeholder projects', function () {
    Project::factory()->create(['title' => 'Placeholder Project One']);

    $this->seed(ProjectSeeder::class);

    expect(Project::where('title', 'like', 'Placeholder%')->count())->toBe(0)
        ->and(Project::count())->toBe(3);
});

test('stops with an error when a project uses a skill that is not seeded', function () {
    Skill::where('name', 'MSSQL')->delete();

    $this->seed(ProjectSeeder::class);
})->throws(RuntimeException::class, 'MSSQL');

test('running the profile seeders twice does not create duplicates', function () {
    $this->seed([
        SkillLevelSeeder::class,
        PersonalInfoSeeder::class,
        EducationSeeder::class,
        SkillSeeder::class,
        ProjectSeeder::class,
    ]);

    expect(PersonalInfo::count())->toBe(1)
        ->and(Skill::count())->toBe(20)
        ->and(Project::count())->toBe(3);
});
