<?php

namespace Josephdlmd\FilamentUx\Tables\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Support\Str;

/**
 * For a List page or a relation manager with tabs, such as All, Active and Stale: the tabs become one button group on
 * the list's search row, before its own toolbar actions (on a List page by itself; a relation manager, which defines
 * its own table(), puts viewChoice() in its toolbarActions()), each labelled with its count ("Stale 8";
 * a button's badge is a corner dot too small to read), the chosen one primary and pressed, instead of a row of tabs above the
 * list. Choosing one filters the list as its tab would. A tab counting 0 has no work waiting and is left out, unless
 * it is the one open; with one tab left there is nothing to choose, and the group is left out too.
 */
trait HasViewChoice
{
    /**
     * No tabs row: the choice is on the search row. The tabs stay defined, so a tab named in the URL still opens.
     */
    public function getTabsContentComponent(): Component
    {
        return Tabs::make()->key('resourceTabs')->hidden();
    }

    /**
     * The resource's table, with the view choice first among its toolbar actions.
     */
    public function table(Table $table): Table
    {
        $table = parent::table($table);

        return $table->toolbarActions([$this->viewChoice(), ...$table->getToolbarActions()]);
    }

    /**
     * The tabs as one button group, each action named "show" and its tab's key ("showStale").
     */
    protected function viewChoice(): ActionGroup
    {
        $counts = collect($this->getCachedTabs())->map(fn (Tab $tab): string => (string) $tab->getBadge());

        $isShown = fn (string $key): bool => $this->activeTab === $key || $counts[$key] !== '0';

        return ActionGroup::make($counts
            ->map(fn (string $count, string $key): Action => Action::make('show'.Str::studly($key))
                ->label(trim("{$this->getCachedTabs()[$key]->getLabel()} {$count}"))
                ->color(fn (): string => $this->activeTab === $key ? 'primary' : 'gray')
                // The chosen one is pressed, so a screen reader says which is open, not the colour alone.
                ->extraAttributes(fn (): array => ['aria-pressed' => $this->activeTab === $key ? 'true' : 'false'])
                ->visible(fn (): bool => $isShown($key))
                ->action(function () use ($key): void {
                    $this->activeTab = $key;
                    $this->updatedActiveTab();
                }))
            ->values()
            ->all())
            ->buttonGroup()
            ->visible(fn (): bool => $counts->keys()->filter($isShown)->count() > 1);
    }
}
