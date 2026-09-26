<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures;

use Filament\Resources\Resource;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ListGadgets;
use Josephdlmd\FilamentUx\Tests\Fixtures\Pages\ViewGadget;

class GadgetResource extends Resource
{
    protected static ?string $model = Gadget::class;

    public static function getPages(): array
    {
        return [
            'index' => ListGadgets::route('/'),
            'view' => ViewGadget::route('/{record}'),
        ];
    }
}
