<?php

namespace Josephdlmd\FilamentUx\Layout\Concerns;

use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Josephdlmd\FilamentUx\Layout\RecordLayout;

/**
 * Builds a ViewRecord page on the record page template. The page describes its layout in recordLayout(); the trait
 * renders it as the page's infolist, makes the Identity bar the page heading and drops the breadcrumbs. The page places
 * its Related lists (getRelationManagersContentComponent()) and widgets in the Main column itself, so they are not
 * appended again below the layout.
 */
trait InteractsWithRecordLayout
{
    private ?RecordLayout $cachedRecordLayout = null;

    /**
     * The page's slots: its Identity bar, Figures, Main column content and Details aside.
     */
    abstract protected function recordLayout(RecordLayout $layout): RecordLayout;

    /**
     * The page's layout, built once per request.
     */
    protected function getRecordLayout(): RecordLayout
    {
        return $this->cachedRecordLayout ??= $this->recordLayout(RecordLayout::make());
    }

    /**
     * The layout is the page's infolist, so ViewRecord never falls back to building and filling the resource form.
     */
    public function infolist(Schema $schema): Schema
    {
        return $this->getRecordLayout()->toSchema($schema);
    }

    /**
     * Only the layout: the page has already placed its Related lists and widgets in the Main column.
     */
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getInfolistContentComponent(),
            ]);
    }

    /**
     * The Identity bar, in place of the page title as heading.
     */
    public function getHeading(): string|Htmlable|null
    {
        return $this->getRecordLayout()->getIdentity() ?? parent::getHeading();
    }

    /**
     * None: the Identity bar names the record, even in a panel that keeps breadcrumbs elsewhere.
     *
     * @return array<string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
