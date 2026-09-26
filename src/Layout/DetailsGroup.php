<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Section;

/**
 * One group of a Details aside, such as Contact, Terms or Notes, under its own small heading and set off from the group
 * above by a hairline, so a long aside reads as a few short lists. The first group has no hairline.
 */
final class DetailsGroup
{
    /**
     * @param  list<Entry|Section>  $components
     */
    public static function make(string $heading, array $components, bool $first = false): Section
    {
        return Section::make($heading)
            ->contained(false)
            ->extraAttributes(['class' => $first ? 'fux-details-group' : 'fux-details-group border-t border-gray-200 pt-4 dark:border-white/10'])
            ->schema($components);
    }
}
