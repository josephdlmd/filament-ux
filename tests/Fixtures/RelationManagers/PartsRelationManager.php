<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Josephdlmd\FilamentUx\Tables\Concerns\HasViewChoice;

class PartsRelationManager extends RelationManager
{
    use HasViewChoice;

    protected static string $relationship = 'parts';

    protected static bool $isLazy = false;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')->badge(fn (): int => $this->getOwnerRecord()->parts()->count()),
            'active' => Tab::make('Active')
                ->badge(fn (): int => $this->getOwnerRecord()->parts()->where('is_active', true)->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('is_active', true)),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([TextColumn::make('name')])
            ->toolbarActions([$this->viewChoice()]);
    }
}
