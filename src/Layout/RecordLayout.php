<?php

namespace Josephdlmd\FilamentUx\Layout;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

/**
 * The record page template. Its slots are the Identity bar (the page heading), the Figures strip, the Main column and
 * the Details aside; it fixes their order and proportions: Figures first, then the Main column (2/3) beside the sticky
 * Details aside (1/3). Use it through InteractsWithRecordLayout on a ViewRecord page.
 */
final class RecordLayout
{
    private ?IdentityBar $identity = null;

    /**
     * @var list<Component>
     */
    private array $figures = [];

    /**
     * @var list<Component>
     */
    private array $main = [];

    /**
     * @var list<Component>
     */
    private array $aside = [];

    /**
     * @var list<Action|ActionGroup>
     */
    private array $asideActions = [];

    /**
     * An empty layout, its slots filled by the page's recordLayout().
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * The Identity bar, which the page shows as its heading.
     */
    public function identity(IdentityBar $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @param  list<Component>  $figures  3 to 5 Figures, or none
     */
    public function figures(array $figures): self
    {
        $this->figures = $figures;

        return $this;
    }

    /**
     * @param  list<Component>  $components  the content, Related lists and widgets
     */
    public function main(array $components): self
    {
        $this->main = $components;

        return $this;
    }

    /**
     * @param  list<Component>  $components  the record's facts
     */
    public function aside(array $components): self
    {
        $this->aside = $components;

        return $this;
    }

    /**
     * The Identity bar, or null when the page keeps Filament's own heading.
     */
    /**
     * Actions on the Details aside's heading row, for what acts on the details it holds (Edit), rather than in the
     * Identity bar.
     *
     * @param  list<Action|ActionGroup>  $actions
     */
    public function asideActions(array $actions): self
    {
        $this->asideActions = $actions;

        return $this;
    }

    public function getIdentity(): ?IdentityBar
    {
        return $this->identity;
    }

    /**
     * The layout as the page's schema: the Figures strip, then the Main column beside the Details aside. A record with
     * no Figures worth a strip declares none and the strip is left out; a strip of 1–2 or more than 5 is refused.
     */
    public function toSchema(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ...($this->figures === [] ? [] : [FiguresStrip::make($this->figures)]),
                Grid::make(['lg' => 3])
                    ->schema([
                        MainColumn::make($this->main),
                        DetailsAside::make($this->aside, $this->asideActions),
                    ]),
            ]);
    }
}
