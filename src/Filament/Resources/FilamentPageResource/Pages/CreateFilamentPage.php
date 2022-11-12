<?php

namespace Beier\FilamentPages\Filament\Resources\FilamentPageResource\Pages;

use Beier\FilamentPages\Filament\Resources\FilamentPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFilamentPage extends CreateRecord
{
    public static function getResource(): string
    {
        return config('filament-pages.filament.resource', FilamentPageResource::class);
    }
}
