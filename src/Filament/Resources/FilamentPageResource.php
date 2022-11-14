<?php

namespace Beier\FilamentPages\Filament\Resources;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Beier\FilamentPages\Filament\Resources\FilamentPageResource\Pages\CreateFilamentPage;
use Beier\FilamentPages\Filament\Resources\FilamentPageResource\Pages\EditFilamentPage;
use Beier\FilamentPages\Filament\Resources\FilamentPageResource\Pages\ListFilamentPages;
use Beier\FilamentPages\Models\FilamentPage;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FilamentPageResource extends Resource
{
    public static function getRecordRouteKeyName(): ?string
    {
        return 'id';
    }

    public static function getModel(): string
    {
        return config('filament-pages.filament.model', FilamentPage::class);
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return __('filament-pages::filament-pages.filament.recordTitleAttribute');
    }

    public static function getModelLabel(): string
    {
        return __('filament-pages::filament-pages.filament.modelLabel');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-pages::filament-pages.filament.pluralLabel');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-pages::filament-pages.filament.navigation.group');
    }

    protected static function getNavigationSort(): ?int
    {
        return (int) __('filament-pages::filament-pages.filament.navigation.sort');
    }

    protected static function getNavigationIcon(): string
    {
        return __('filament-pages::filament-pages.filament.navigation.icon');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        static::getPrimaryColumnSchema(),
                        ...static::getTemplateSchemas(),
                    ])
                    ->columnSpan(['lg' => 7]),

                static::getSecondaryColumnSchema(),

            ])
            ->columns([
                'sm' => 9,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament-pages::filament-pages.filament.form.title.label'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('filament-pages::filament-pages.filament.form.slug.label'))
                    ->icon('heroicon-o-external-link')
                    ->iconPosition('after')
                    ->getStateUsing(fn (FilamentPage $record) => url($record->slug))
                    ->searchable()
                    ->url(
                        url: fn (FilamentPage $record) => url($record->slug),
                        shouldOpenInNewTab: true
                    )
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                BadgeColumn::make('status')
                    ->getStateUsing(fn (FilamentPage $record): string => $record->published_at->isPast() && ($record->published_until?->isFuture() ?? true) ? __('filament-pages::filament-pages.filament.table.status.published') : __('filament-pages::filament-pages.filament.table.status.draft'))
                    ->colors([
                        'success' => __('filament-pages::filament-pages.filament.table.status.published'),
                        'warning' => __('filament-pages::filament-pages.filament.table.status.draft'),
                    ]),

                TextColumn::make('published_at')
                    ->label(__('filament-pages::filament-pages.filament.form.published_at.label'))
                    ->date(__('filament-pages::filament-pages.filament.form.published_at.displayFormat')),
            ])
            ->filters([
                Filter::make('published_at')
                    ->form([
                        DatePicker::make('published_from')
                            ->label(__('filament-pages::filament-pages.filament.form.published_at.label'))
                            ->placeholder(fn ($state): string => '18. November '.now()->subYear()->format('Y')),
                        DatePicker::make('published_until')
                            ->label(__('filament-pages::filament-pages.filament.form.published_until.label'))
                            ->placeholder(fn ($state): string => now()->format('d. F Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Published from '.Carbon::parse($data['published_at'])->toFormattedDateString();
                        }
                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Published until '.Carbon::parse($data['published_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                EditAction::make(),

                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPrimaryColumnSchema(): Component
    {
        return Card::make()
            ->columns(2)
            ->schema([
                ...static::insertBeforePrimaryColumnSchema(),
                TextInput::make('title')
                    ->label(__('filament-pages::filament-pages.filament.form.title.label'))
                    ->columnSpan(1)
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->label(__('filament-pages::filament-pages.filament.form.slug.label'))
                    ->columnSpan(1)
                    ->required()
                    ->unique(FilamentPage::class, 'slug', ignoreRecord: true),
                ...static::insertAfterPrimaryColumnSchema(),
            ]);
    }

    public static function getSecondaryColumnSchema(): Component
    {
        return Card::make()
            ->schema([
                ...static::insertBeforeSecondaryColumnSchema(),
                Select::make('data.template')
                    ->reactive()
                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $set('data.templateName', Str::snake(self::getTemplateName($state))))
                    ->afterStateHydrated(fn (string $context, $state, callable $set) => $set('data.templateName', Str::snake(self::getTemplateName($state))))
                    ->options(static::getTemplates()),

                Hidden::make('data.templateName')
                    ->reactive(),

                DatePicker::make('published_at')
                    ->label(__('filament-pages::filament-pages.filament.form.published_at.label'))
                    ->displayFormat(__('filament-pages::filament-pages.filament.form.published_at.displayFormat'))
                    ->default(now()),

                DatePicker::make('published_until')
                    ->label(__('filament-pages::filament-pages.filament.form.published_until.label'))
                    ->displayFormat(__('filament-pages::filament-pages.filament.form.published_until.displayFormat')),

                Placeholder::make('created_at')
                    ->label(__('filament-pages::filament-pages.filament.form.created_at.label'))
                    ->hidden(fn (?FilamentPage $record) => $record === null)
                    ->content(fn (FilamentPage $record): string => $record->created_at->diffForHumans()),

                Placeholder::make('updated_at')
                    ->label(__('filament-pages::filament-pages.filament.form.updated_at.label'))
                    ->hidden(fn (?FilamentPage $record) => $record === null)
                    ->content(fn (FilamentPage $record): string => $record->updated_at->diffForHumans()),
                ...static::insertAfterSecondaryColumnSchema(),
            ])
            ->columnSpan(['lg' => 2]);
    }

    public static function insertBeforePrimaryColumnSchema(): array
    {
        return [];
    }

    public static function insertAfterPrimaryColumnSchema(): array
    {
        return [];
    }

    public static function insertBeforeSecondaryColumnSchema(): array
    {
        return [];
    }

    public static function insertAfterSecondaryColumnSchema(): array
    {
        return [];
    }

    /**
     * @return Collection<FilamentPageTemplate>
     */
    public static function getTemplateClasses(): Collection
    {
        return collect(config('filament-pages.templates', []));
    }

    /**
     * @return Collection<FilamentPageTemplate>
     */
    public static function getTemplates(): Collection
    {
        return static::getTemplateClasses()
            ->mapWithKeys(fn ($class) => [$class => $class::title()]);
    }

    public static function getTemplateName($class): string
    {
        return Str::of($class)->afterLast('\\')->snake()->toString();
    }

    public static function getTemplateSchemas(): array
    {
        return static::getTemplateClasses()
            ->map(fn ($class) => Group::make($class::schema())
                ->afterStateHydrated(fn ($component, $state) => $component->getChildComponentContainer()->fill($state))
                ->statePath('data.content')
                ->visible(fn ($get) => $get('data.template') === $class)
            )
            ->toArray();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['data.content'] = $data['temp_content'][static::getTemplateName($data['template'])];
        unset($data['temp_content']);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['data.content'] = $data['temp_content'][static::getTemplateName($data['template'])];
        unset($data['temp_content']);

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFilamentPages::route('/'),
            'create' => CreateFilamentPage::route('/create'),
            'edit' => EditFilamentPage::route('/{record:id}/edit'),
        ];
    }
}
