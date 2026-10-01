<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures;

use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ListGadgets;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ViewGadget;

class GadgetResource extends Resource
{
    protected static ?string $model = Gadget::class;

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('name')]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PartsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGadgets::route('/'),
            'view' => ViewGadget::route('/{record}'),
        ];
    }
}
