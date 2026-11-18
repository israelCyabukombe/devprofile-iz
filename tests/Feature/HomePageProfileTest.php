<?php

use App\Models\Education;
use App\Models\PersonalInfo;
use Database\Seeders\EducationSeeder;
use Database\Seeders\PersonalInfoSeeder;

test('the home page shows the seeded profile in the sidebar', function () {
    $this->seed([PersonalInfoSeeder::class, EducationSeeder::class]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Israel Cyabukombe')
        ->assertSee('Software Engineer')
        ->assertSee('B.Sc. in Computer Science')
        ->assertSee('South Dakota State University (SDSU)')
        ->assertSee('Minor in Mathematics');
});

test('the sidebar shows the profile photo', function () {
    $personalInfo = PersonalInfo::factory()->create([
        'profile_photo_path' => 'images/IMG_0002.jpg',
    ]);

    $response = $this->get(route('home'));

    $response->assertSee(asset('images/IMG_0002.jpg'), false)
        ->assertSee('Portrait of '.$personalInfo->fullName());

    expect(public_path('images/IMG_0002.jpg'))->toBeFile();
});

test('the sidebar links to GitHub and LinkedIn', function () {
    PersonalInfo::factory()->create([
        'github_url' => 'https://github.com/example',
        'linkedin_url' => 'https://www.linkedin.com/in/example',
    ]);

    $response = $this->get(route('home'));

    $response->assertSee('href="https://github.com/example"', false)
        ->assertSee('href="https://www.linkedin.com/in/example"', false);
});

test('the sidebar hides links and photo that are not set', function () {
    PersonalInfo::factory()->create([
        'profile_photo_path' => null,
        'github_url' => null,
        'linkedin_url' => null,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertDontSee('class="profile-photo"', false)
        ->assertDontSee('aria-label="GitHub"', false)
        ->assertDontSee('aria-label="LinkedIn"', false);
});

test('education is listed in sort order', function () {
    $personalInfo = PersonalInfo::factory()->create();
    Education::factory()->for($personalInfo)->create(['degree' => 'Second Degree', 'sort_order' => 2]);
    Education::factory()->for($personalInfo)->create(['degree' => 'First Degree', 'sort_order' => 1]);

    $response = $this->get(route('home'));

    $response->assertSeeInOrder(['First Degree', 'Second Degree']);
});

test('the home page still loads before the database is seeded', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Profile coming soon');
});
