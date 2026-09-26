<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;

/**
 * The record page's content, its Related lists and its widgets, two thirds wide from `lg`. It is flat: its regions are
 * separated by spacing, never boxed, and carry a heading only where the content doesn't explain itself.
 */
final class MainColumn
{
    /**
     * @param  list<Component>  $components
     */
    public static function make(array $components): Group
    {
        return Group::make($components)
            ->columnSpan(['lg' => 2]);
    }
}
