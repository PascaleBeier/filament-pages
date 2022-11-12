<?php

namespace Beier\FilamentPages\Filament\Resources\FilamentPageResource\Pages;

use Beier\FilamentPages\Filament\Resources\FilamentPageResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFilamentPage extends EditRecord
{
    public static function getResource(): string
    {
        return config('filament-pages.filament.resource', FilamentPageResource::class);
    }

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
