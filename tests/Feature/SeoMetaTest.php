<?php

use App\Models\PersonalInfo;
use Illuminate\Testing\TestResponse;

/**
 * Decode the JSON-LD block in the page's <head>, or return null when there is none.
 *
 * @return array<string, mixed>|null
 */
function decodeStructuredData(TestResponse $response): ?array
{
    $html = $response->getContent();
    $pattern = '#<script type="application/ld\+json">(.*?)</script>#s';

    if (preg_match($pattern, $html, $matches) !== 1) {
        return null;
    }

    return json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
}

test('the page title is the name and job title', function () {
    PersonalInfo::factory()->create([
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'job_title' => 'Software Engineer',
    ]);

    $response = $this->get(route('home'));

    $response->assertSee('<title>Ada Lovelace — Software Engineer</title>', false)
        ->assertSee('<meta property="og:title" content="Ada Lovelace — Software Engineer">', false);
});

test('the meta description is the summary on one line', function () {
    PersonalInfo::factory()->create([
        'summary_text' => "Builds healthcare\n   applications.",
    ]);

    $response = $this->get(route('home'));

    $response->assertSee('<meta name="description" content="Builds healthcare applications.">', false)
        ->assertSee('<meta property="og:description" content="Builds healthcare applications.">', false);
});

test('a long summary is cut to about 160 characters at a word boundary', function () {
    $longSummary = str_repeat('healthcare ', 30);
    PersonalInfo::factory()->create(['summary_text' => $longSummary]);

    $response = $this->get(route('home'));

    preg_match('#<meta name="description" content="([^"]*)">#', $response->getContent(), $matches);
    $description = $matches[1];

    expect(mb_strlen($description))->toBeLessThanOrEqual(163)
        ->and($description)->toEndWith('healthcare...');
});

test('the canonical and Open Graph URLs point to the home page', function () {
    PersonalInfo::factory()->create();

    $response = $this->get(route('home'));

    $response->assertSee('<link rel="canonical" href="'.route('home').'">', false)
        ->assertSee('<meta property="og:url" content="'.route('home').'">', false)
        ->assertSee('<meta name="twitter:card" content="summary">', false);
});

test('the Open Graph image is the absolute URL of the profile photo', function () {
    PersonalInfo::factory()->create([
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'profile_photo_path' => 'images/IMG_0002.jpg',
    ]);

    $response = $this->get(route('home'));

    $response->assertSee('<meta property="og:image" content="'.asset('images/IMG_0002.jpg').'">', false)
        ->assertSee('<meta property="og:image:alt" content="Portrait of Ada Lovelace">', false);
});

test('there is no Open Graph image when the profile has no photo', function () {
    PersonalInfo::factory()->create(['profile_photo_path' => null]);

    $response = $this->get(route('home'));

    $response->assertDontSee('og:image', false);
});

test('the page describes the person with JSON-LD', function () {
    PersonalInfo::factory()->create([
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'job_title' => 'Software Engineer',
        'profile_photo_path' => 'images/IMG_0002.jpg',
        'github_url' => 'https://github.com/example',
        'linkedin_url' => 'https://www.linkedin.com/in/example',
    ]);

    $response = $this->get(route('home'));

    expect(decodeStructuredData($response))->toBe([
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'Ada Lovelace',
        'jobTitle' => 'Software Engineer',
        'url' => route('home'),
        'image' => asset('images/IMG_0002.jpg'),
        'sameAs' => ['https://github.com/example', 'https://www.linkedin.com/in/example'],
    ]);
});

test('the JSON-LD leaves out the photo and links that are not set', function () {
    PersonalInfo::factory()->create([
        'profile_photo_path' => null,
        'github_url' => null,
        'linkedin_url' => null,
    ]);

    $response = $this->get(route('home'));
    $structuredData = decodeStructuredData($response);

    expect($structuredData)->not->toHaveKey('image')
        ->and($structuredData)->not->toHaveKey('sameAs');
});

test('the page has a generic title and description before the database is seeded', function () {
    $response = $this->get(route('home'));

    $response->assertSee('<title>Israel Cyabu</title>', false)
        ->assertSee('<meta name="description" content="Career profile, skills and project samples.">', false)
        ->assertDontSee('og:image', false);

    expect(decodeStructuredData($response))->toBeNull();
});

test('the profile photo is compressed for the web', function () {
    $photoPath = public_path('images/IMG_0002.jpg');
    [$widthInPixels] = getimagesize($photoPath);

    expect(filesize($photoPath))->toBeLessThan(300 * 1024)
        ->and($widthInPixels)->toBe(600);
});
