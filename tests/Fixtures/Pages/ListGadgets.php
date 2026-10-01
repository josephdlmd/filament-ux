<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Josephdlmd\FilamentUx\Tables\Concerns\HasViewChoice;
use Josephdlmd\FilamentUx\Tests\Fixtures\Gadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\GadgetResource;

class ListGadgets extends ListRecords
{
    use HasViewChoice;

    protected static string $resource = GadgetResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'to_check' => Tab::make('To check')
                ->badge(fn (): int => Gadget::query()->where('status', 'to_check')->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'to_check')),
            'to_retire' => Tab::make('To retire')
                ->badge(fn (): int => Gadget::query()->where('status', 'to_retire')->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'to_retire')),
        ];
    }
}
