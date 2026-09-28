<?php

namespace Josephdlmd\FilamentUx\Layout;

use Closure;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

/**
 * One Figure in a record page's Figures strip: a label over a large value, both aligned to the end so they read as
 * one unit and numbers line up, only as wide as the wider of the two, so the strip packs its Figures together. Its working, when it needs any, is at most one caption line of data given with
 * caption(), such as "ex-VAT · 3 offers · 1 VAT unknown", never a sentence.
 *
 * Filament aligns an entry's label to the start, so the label stays the entry's term for screen readers and a copy is
 * shown above the value, at the end, with Filament's own slots and no CSS. The type scale is the Details aside's: a
 * gray regular-weight label over the value in the text colour, and a caption smaller and gray.
 */
class Figure extends TextEntry
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->size(TextSize::Large)
            ->weight(FontWeight::SemiBold)
            ->alignEnd()
            ->grow(false)
            ->hiddenLabel()
            ->aboveContent(fn (Figure $component): Schema => Schema::end([
                Text::make($component->getLabel())
                    ->color('gray'),
            ]));
    }

    /**
     * The Figure's one caption line of data under its value, aligned with it; none when the caption is null or empty.
     * Plain text is set end-aligned too (Tailwind's `text-end`), so a caption that wraps still lines up with the value,
     * and small and gray, quieter than the value it explains.
     *
     * @param  string|array<int, mixed>|Closure|null  $caption  text, prime components such as a badge, or a closure giving either
     */
    public function caption(string|array|Closure|null $caption): static
    {
        return $this->belowContent(function (Figure $component) use ($caption): ?Schema {
            $content = $component->evaluate($caption);

            if (blank($content)) {
                return null;
            }

            return Schema::end(array_map(
                fn (mixed $part): mixed => is_string($part)
                    ? Text::make($part)->color('gray')->size(TextSize::ExtraSmall)->extraAttributes(['class' => 'text-end'])
                    : $part,
                is_array($content) ? $content : [$content],
            ));
        });
    }
}
