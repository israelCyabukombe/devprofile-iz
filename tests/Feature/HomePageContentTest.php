<?php

use App\Enums\SkillCategory;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;

test('the right column shows the job title and summary', function () {
    PersonalInfo::factory()->create([
        'job_title' => 'Software Engineer',
        'summary_text' => 'Builds healthcare applications.',
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Software Engineer')
        ->assertSee('Builds healthcare applications.');
});

test('skills appear under their category headings in enum order', function () {
    $personalInfo = PersonalInfo::factory()->create();
    Skill::factory()->for($personalInfo)->inCategory(SkillCategory::Other)->create(['name' => 'ToolSkill']);
    Skill::factory()->for($personalInfo)->inCategory(SkillCategory::BackEnd)->create(['name' => 'BackSkill']);
    Skill::factory()->for($personalInfo)->inCategory(SkillCategory::FrontEnd)->create(['name' => 'FrontSkill']);
    Skill::factory()->for($personalInfo)->inCategory(SkillCategory::Framework)->create(['name' => 'FrameworkSkill']);

    $response = $this->get(route('home'));

    $response->assertSeeInOrder([
        'Front End', 'FrontSkill',
        'Back End', 'BackSkill',
        'Frameworks', 'FrameworkSkill',
        'Other Tools', 'ToolSkill',
    ]);
});

test('categories without skills are left out', function () {
    $personalInfo = PersonalInfo::factory()->create();
    Skill::factory()->for($personalInfo)->inCategory(SkillCategory::BackEnd)->create(['name' => 'BackSkill']);

    $response = $this->get(route('home'));

    $response->assertSee('Back End')
        ->assertDontSee('Front End')
        ->assertDontSee('Other Tools');
});

test('projects appear in sort order with their tech stack', function () {
    PersonalInfo::factory()->create();
    $secondProject = Project::factory()->create(['title' => 'Second Project', 'sort_order' => 2]);
    $firstProject = Project::factory()->create(['title' => 'First Project', 'sort_order' => 1]);
    $firstProject->skills()->attach(Skill::factory()->create(['name' => 'StackSkill']));

    $response = $this->get(route('home'));

    $response->assertSeeInOrder(['First Project', 'StackSkill', 'Second Project'])
        ->assertSee('id="project-'.$firstProject->id.'-tech-stack"', false)
        ->assertDontSee('id="project-'.$secondProject->id.'-tech-stack"', false);
});

test('project cards link to the repo and live site when they are set', function () {
    PersonalInfo::factory()->create();
    Project::factory()->create([
        'title' => 'Linked Project',
        'repo_url' => 'https://github.com/example/linked',
        'live_url' => 'https://linked.example.com',
    ]);
    Project::factory()->create([
        'title' => 'Unlinked Project',
        'repo_url' => null,
        'live_url' => null,
    ]);

    $response = $this->get(route('home'));

    $response->assertSee('href="https://github.com/example/linked"', false)
        ->assertSee('href="https://linked.example.com"', false)
        ->assertDontSee('Source code for Unlinked Project')
        ->assertDontSee('Live site for Unlinked Project');
});

test('the projects section shows a message when there are no projects', function () {
    PersonalInfo::factory()->create();

    $response = $this->get(route('home'));

    $response->assertSee('Project Samples')
        ->assertSee('No projects yet.');
});
