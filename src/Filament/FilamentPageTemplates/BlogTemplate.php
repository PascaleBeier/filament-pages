<?php

namespace Beier\FilamentPages\Filament\FilamentPageTemplates;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;

final class BlogTemplate implements FilamentPageTemplate
{
    public static function title(): string
    {
        return 'Simple Blog';
    }

    public static function schema(): array
    {
        return [
            Card::make()
                ->schema([
                    MarkdownEditor::make('content')
                        ->label(__('Content')),
                    TextInput::make('author'),
                ]),
        ];
    }
}
