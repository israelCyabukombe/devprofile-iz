<?php

use App\Enums\SkillCategory;
use App\Models\Education;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillLevel;

test('personal info has many skills', function () {
    $personalInfo = PersonalInfo::factory()->create();
    Skill::factory()->count(3)->for($personalInfo)->create();

    expect($personalInfo->skills)->toHaveCount(3);
});

test('personal info lists education in sort order', function () {
    $personalInfo = PersonalInfo::factory()->create();
    Education::factory()->for($personalInfo)->create(['degree' => 'Second', 'sort_order' => 2]);
    Education::factory()->for($personalInfo)->create(['degree' => 'First', 'sort_order' => 1]);

    $degrees = $personalInfo->education->pluck('degree')->all();

    expect($degrees)->toBe(['First', 'Second']);
});

test('personal info builds the full name', function () {
    $personalInfo = PersonalInfo::factory()->make([
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
    ]);

    expect($personalInfo->fullName())->toBe('Ada Lovelace');
});

test('skill belongs to a skill level', function () {
    $skillLevel = SkillLevel::factory()->create(['description' => 'Advanced']);
    $skill = Skill::factory()->for($skillLevel)->create();

    expect($skill->skillLevel->description)->toBe('Advanced');
});

test('skill category is cast to the enum', function () {
    $skill = Skill::factory()->inCategory(SkillCategory::BackEnd)->create();

    expect($skill->fresh()->category)->toBe(SkillCategory::BackEnd);
});

test('project has many skills and skill has many projects', function () {
    $project = Project::factory()->create();
    $skills = Skill::factory()->count(2)->create();

    $project->skills()->attach($skills);

    expect($project->skills)->toHaveCount(2)
        ->and($skills->first()->projects->first()->id)->toBe($project->id);
});
