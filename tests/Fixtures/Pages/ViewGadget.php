<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures\Pages;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Josephdlmd\FilamentUx\Entries\ContactEntry;
use Josephdlmd\FilamentUx\Layout\Concerns\InteractsWithRecordLayout;
use Josephdlmd\FilamentUx\Layout\DetailsGroup;
use Josephdlmd\FilamentUx\Layout\Figure;
use Josephdlmd\FilamentUx\Layout\IdentityBar;
use Josephdlmd\FilamentUx\Layout\RecordLayout;
use Josephdlmd\FilamentUx\Tests\Fixtures\Gadget;
use Josephdlmd\FilamentUx\Tests\Fixtures\GadgetResource;

class ViewGadget extends ViewRecord
{
    use InteractsWithRecordLayout;

    protected static string $resource = GadgetResource::class;

    /**
     * How many Figures the page declares, so a test can give it too few or too many.
     */
    public static int $figureCount = 3;

    /**
     * How many of the declared Figures are hidden from the viewer.
     */
    public static int $hiddenFigures = 0;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('restock')->action(fn (): null => null),
            Action::make('rename')->action(fn (): bool => $this->record->update(['name' => 'Renamed sprocket'])),
        ];
    }

    protected function recordLayout(RecordLayout $layout): RecordLayout
    {
        return $layout
            ->identity(IdentityBar::make($this->record->code, $this->record->name)->status(ucfirst($this->record->status), 'success'))
            ->figures(array_map(
                fn (Figure $figure, int $index): Figure => $figure->visible($index >= self::$hiddenFigures),
                $figures = array_slice([
                    Figure::make('stock')->label('Stock figure')->state(12)->caption('ex-VAT · 3 offers'),
                    Figure::make('reorder')->label('Reorder figure')->state(4),
                    Figure::make('lead')->label('Lead time figure')->state('3 days'),
                    Figure::make('shelf')->label('Shelf figure')->state('A2'),
                    Figure::make('batch')->label('Batch figure')->state('B-17'),
                    Figure::make('supplier')->label('Supplier figure')->state('Acme'),
                ], 0, self::$figureCount),
                array_keys($figures),
            ))
            ->main([
                TextEntry::make('notes')->label('Main notes')->state('Handle with care'),
                $this->getRelationManagersContentComponent(),
            ])
            ->aside([
                DetailsGroup::make('Identity', [
                    TextEntry::make('code')->label('Aside code'),
                ], first: true),
                DetailsGroup::make('Contact', [
                    ContactEntry::phone('mobile'),
                    ContactEntry::phone('landline', viber: false),
                    ContactEntry::email('email'),
                    ContactEntry::address('address', country: fn (Gadget $record): ?string => $record->country),
                ]),
            ])
            ->asideActions([
                Action::make('editDetails')->label('Edit details')->action(fn (): null => null),
            ]);
    }
}
