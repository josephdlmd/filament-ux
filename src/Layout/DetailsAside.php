<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;

/**
 * The record's facts, one third wide from `lg`, beside the Main column: the only boxed region on a record page, compact,
 * with inline labels, and sticky so it stays in view while a long Related list scrolls. It has no heading, as its box
 * and its groups' headings already say what it holds; its actions, when given, sit on a header row of their own. The stickiness is the one class
 * in the package's `layout.css`.
 */
final class DetailsAside
{
    public const string CSS_CLASS = 'fux-details-aside';

    /**
     * @param  list<Component>  $components
     * @param  list<Action|ActionGroup>  $actions  shown on the aside's heading row, such as Edit for the details it holds
     */
    public static function make(array $components, array $actions = []): Section
    {
        return Section::make()
            ->key('details::section', isInheritable: false)
            ->headerActions($actions)
            ->compact()
            ->inlineLabel()
            ->columnSpan(['lg' => 1])
            ->extraAttributes(['class' => self::CSS_CLASS])
            ->schema($components);
    }
}
