<?php

use Beier\FilamentPages\Filament\FilamentPageTemplates\DefaultTemplate;
use Beier\FilamentPages\Models\FilamentPage;
use function Pest\Laravel\assertDatabaseHas;

it('can create pages', function () {
    FilamentPage::create([
        'title' => 'My Page',
        'slug' => 'my-page',
        'data' => [
            'content' => ['test-element' => 'lorem'],
            'template' => DefaultTemplate::class,
            'templateName' => 'default_template',
        ],
        'created_at' => now(),
        'published_at' => now(),
        'published_until' => null,
        'updated_at' => now(),
    ]);

    assertDatabaseHas('filament_pages', ['title' => 'My Page']);
});
