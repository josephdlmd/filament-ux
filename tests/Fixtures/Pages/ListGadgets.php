<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Josephdlmd\FilamentUx\Tests\Fixtures\GadgetResource;

class ListGadgets extends ListRecords
{
    protected static string $resource = GadgetResource::class;

    public function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('name')]);
    }
}
