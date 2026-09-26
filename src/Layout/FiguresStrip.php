<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use InvalidArgumentException;

/**
 * The row of 3 to 5 Figures under the Identity bar. The grid always has five columns from `lg`, so a Figure is the same
 * width on every record page; a Figure hidden from a viewer is left out and the others close up. A page with more
 * candidate figures moves the less important ones into the Details aside, and one with fewer than 3 has no strip; a
 * strip given fewer than 3 or more than 5 is refused.
 */
final class FiguresStrip
{
    public const int MIN_FIGURES = 3;

    public const int MAX_FIGURES = 5;

    /**
     * @param  list<Component>  $figures
     */
    public static function make(array $figures): Grid
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

        return Grid::make(['default' => 2, 'lg' => self::MAX_FIGURES])
            ->schema($figures);
    }
}
