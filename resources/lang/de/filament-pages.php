<?php

return [
    'filament' => [
        'recordTitleAttribute' => config('filament-pages.filament.recordTitleAttribute', 'title'),
        'modelLabel' => 'Seite',
        'pluralLabel' => 'Seiten',
        'navigation' => [
            'icon' => config('filament-pages.filament.navigation.icon', 'heroicon-o-document'),
            'group' => 'Inhalte',
            'sort' => config('filament-pages.filament.navigation.sort', null),
        ],
        'table' => [
            'status' => [
                'published' => 'Veröffentlicht',
                'draft' => 'Entwurf',
            ],
            'title' => 'Titel',
            'created_at' => 'Erstellt am',
        ],
        'form' => [
            'title' => [
                'label' => 'Titel',
            ],
            'slug' => [
                'label' => 'URL-Segment',
            ],
            'published_at' => [
                'label' => 'Veröffentlicht am',
                'displayFormat' => 'd. F Y',
            ],
            'published_until' => [
                'label' => 'Veröffentlicht bis',
                'displayFormat' => 'd. F Y',
            ],
            'created_at' => [
                'label' => 'Erstellt',
            ],
            'updated_at' => [
                'label' => 'Aktualisiert',
            ],
        ],
    ],
];
