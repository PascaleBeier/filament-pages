<?php

return [
    'filament' => [
        'recordTitleAttribute' => config('filament-pages.filament.recordTitleAttribute', 'title'),
        'modelLabel' => config('filament-pages.filament.modelLabel', 'Page'),
        'pluralLabel' => config('filament-pages.filament.pluralLabel', 'Pages'),
        'navigation' => [
            'icon' => config('filament-pages.filament.navigation.icon', 'heroicon-o-document'),
            'group' => config('filament-pages.filament.navigation.group', null),
            'sort' => config('filament-pages.filament.navigation.sort', null),
        ],
        'table' => [
            'status' => [
                'published' => 'published',
                'draft' => 'draft',
            ],
            'title' => 'Title',
            'created_at' => 'Created at',
        ],
        'form' => [
            'title' => [
                'label' => 'Title',
            ],
            'slug' => [
                'label' => 'Slug',
            ],
            'published_at' => [
                'label' => 'Published at',
                'displayFormat' => 'd. M Y',
            ],
            'published_until' => [
                'label' => 'Published until',
                'displayFormat' => 'd. M Y',
            ],
            'created_at' => [
                'label' => 'Created at',
            ],
            'updated_at' => [
                'label' => 'Updated at',
            ],
        ],
    ],
];
