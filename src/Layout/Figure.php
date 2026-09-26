<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

/**
 * One Figure in a record page's Figures strip: a label over a large, right-aligned value. Its working, when it needs
 * any, is at most one caption line of data given with `belowContent()`, such as "ex-VAT · 3 offers · 1 VAT unknown",
 * never a sentence.
 */
final class Figure
{
    public static function make(string $name): TextEntry
    {
        return TextEntry::make($name)
            ->size(TextSize::Large)
            ->weight(FontWeight::SemiBold)
            ->alignEnd();
    }
}
