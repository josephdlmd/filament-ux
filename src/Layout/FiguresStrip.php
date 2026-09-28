<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Flex;
use InvalidArgumentException;

/**
 * The row of 3 to 5 Figures under the Identity bar, packed together from the start of the page, each only as wide as
 * its label or value, with a fixed gap between them (`layout.css`), so a few short numbers read as one group instead of
 * floating across the page. The row wraps on a narrow screen. A page with more candidate figures moves the less
 * important ones into the Details aside, and one with fewer than 3 has no strip; a strip given fewer than 3 or more
 * than 5 is refused.
 */
final class FiguresStrip
{
    public const int MIN_FIGURES = 3;

    public const int MAX_FIGURES = 5;

    public const string CSS_CLASS = 'fux-figures-strip';

    /**
     * @param  list<Component>  $figures
     */
    public static function make(array $figures): Flex
    {
        $count = count($figures);

        if ($count < self::MIN_FIGURES || $count > self::MAX_FIGURES) {
            throw new InvalidArgumentException(sprintf(
                'A Figures strip holds %d to %d Figures; %d given.',
                self::MIN_FIGURES,
                self::MAX_FIGURES,
                $count,
            ));
        }

        return Flex::make($figures)
            ->extraAttributes(['class' => self::CSS_CLASS]);
    }
}
