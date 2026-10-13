<?php

test('the navbar shows the brand and links', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Israel Cyabu')
        ->assertSee('About')
        ->assertSee('Résumé')
        ->assertSee('My GitHub')
        ->assertSee('My LinkedIn');
});

test('the résumé link points to the PDF in public', function () {
    $response = $this->get(route('home'));

    $response->assertSee(asset('ISRAEL%20CYABUKOMBE%20RESUME.pdf'), false);

    expect(public_path('ISRAEL CYABUKOMBE RESUME.pdf'))->toBeFile();
});

test('the navbar has a single light and dark theme toggle', function () {
    $response = $this->get(route('home'));

    $response->assertSee('data-theme-toggle', false)
        ->assertDontSee('data-theme="mid"', false);
});

test('the theme is applied in the head before the page paints', function () {
    $response = $this->get(route('home'));

    $response->assertSeeInOrder([
        '<head>',
        'applyInitialTheme',
        'prefers-color-scheme: dark',
        '</head>',
    ], false);
});
