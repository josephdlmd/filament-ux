<?php

namespace Josephdlmd\FilamentUx\Tables\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Str;

/**
 * For a relation manager with tabs, such as All, Active and Stale: the tabs become one button group on the list's
 * search row (put viewChoice() in the table's toolbarActions()), each labelled with its count ("Stale 8"; a button's
 * badge is a corner dot too small to read), the chosen one primary, instead of a row of tabs above the list. Choosing
 * one filters the list as its tab would.
 */
trait HasViewChoice
{
    /**
     * The list alone, without the tabs row: the choice is on its search row.
     */
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::RESOURCE_RELATION_MANAGER_BEFORE),
                EmbeddedTable::make(),
                RenderHook::make(PanelsRenderHook::RESOURCE_RELATION_MANAGER_AFTER),
            ]);
    }

    /**
     * The tabs as one button group, each action named "show" and its tab's key ("showStale").
     */
    protected function viewChoice(): ActionGroup
    {
        return ActionGroup::make(collect($this->getCachedTabs())
            ->map(fn (Tab $tab, string $key): Action => Action::make('show'.Str::studly($key))
                ->label(trim("{$tab->getLabel()} {$tab->getBadge()}"))
                ->color(fn (): string => $this->activeTab === $key ? 'primary' : 'gray')
                ->action(function () use ($key): void {
                    $this->activeTab = $key;
                    $this->updatedActiveTab();
                }))
            ->values()
            ->all())
            ->buttonGroup();
    }
}
