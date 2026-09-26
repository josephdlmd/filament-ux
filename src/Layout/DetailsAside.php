<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;

/**
 * The record's facts, one third wide from `lg`, beside the Main column: the only boxed region on a record page, compact,
 * with inline labels, and sticky so it stays in view while a long Related list scrolls. The stickiness is the one class
 * in the package's `layout.css`.
 */
final class DetailsAside
{
    public const string CSS_CLASS = 'fux-details-aside';

    /**
     * @param  list<Component>  $components
     */
    public static function make(array $components): Section
    {
        return Section::make('Details')
            ->compact()
            ->inlineLabel()
            ->columnSpan(['lg' => 1])
            ->extraAttributes(['class' => self::CSS_CLASS])
            ->schema($components);
    }
}
