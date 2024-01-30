<?php

namespace Beier\FilamentPages;

use Beier\FilamentPages\Contracts\Renderer;
use Beier\FilamentPages\Filament\Resources\FilamentPageResource;
use Beier\FilamentPages\Renderer\SimplePageRenderer;
use Filament\ServiceProvider as FilamentServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;

class FilamentPagesServiceProvider extends FilamentServiceProvider
{
    public static string $name = 'filament-pages';

    protected function getResources(): array
    {
        return [
            config('filament-pages.filament.resource', FilamentPageResource::class),
        ];
    }

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_filament_pages_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('pascalebeier/filament-pages');
            });
    }

    public function boot(): void
    {
        parent::boot();
        $this->bindRenderer();
    }

    protected function bindRenderer(): void
    {
        $this->app->bind(Renderer::class, function ($app) {
            return $app->make(config('filament-pages.renderer', SimplePageRenderer::class));
        });
    }
}
