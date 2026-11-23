<?php

use App\Models\PersonalInfo;
use App\Models\Project;

test('the page content lines up with the navbar container', function () {
    PersonalInfo::factory()->create();

    $response = $this->get(route('home'));

    $response->assertSee('<div class="container">', false)
        ->assertDontSee('container-fluid', false);
});

test('the two columns only sit side by side from the lg breakpoint', function () {
    PersonalInfo::factory()->create();

    $response = $this->get(route('home'));

    $response->assertSeeInOrder([
        'class="col-lg-4 mb-4 mb-lg-0 profile-sidebar"',
        'class="col-lg-8 ps-lg-4"',
        'class="home-content"',
    ], false)
        ->assertDontSee('col-md-4', false)
        ->assertDontSee('col-md-8', false);
});

test('the intro job title is hidden while the columns are stacked', function () {
    PersonalInfo::factory()->create(['job_title' => 'Software Engineer']);

    $response = $this->get(route('home'));

    $response->assertSee('<h4 class="mb-3 d-none d-lg-block">Software Engineer</h4>', false)
        ->assertSee('<h4 class="mb-0">Software Engineer</h4>', false);
});

test('project cards use less padding on phones', function () {
    PersonalInfo::factory()->create();
    Project::factory()->create();

    $response = $this->get(route('home'));

    $response->assertSee('class="project-card p-3 p-sm-4 mb-4"', false);
});
