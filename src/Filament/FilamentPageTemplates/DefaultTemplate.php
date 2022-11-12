<?php

namespace Beier\FilamentPages\Filament\FilamentPageTemplates;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;

final class DefaultTemplate implements FilamentPageTemplate
{
    public static function title(): string
    {
        return 'Simple Page';
    }

    public static function schema(): array
    {
        return [
            Card::make()
                ->schema([
                    RichEditor::make('content')
                        ->label(__('Content')),
                ]),
        ];
    }
}
