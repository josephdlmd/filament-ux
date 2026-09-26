# Page templates

Every screen in the panel is one of three templates: a **Record page**, a **List page** or a **Manage page**. Each reads as one designed screen rather than a stack of components, and two apps built on them feel the same. The Record page is assembled with this package's building blocks (`Josephdlmd\FilamentUx\Layout`), which use only Filament's own schema components, page methods and design tokens, so they look right under any theme. Tags **C**, **S**, **P** as defined in `SKILL.md`; the templates are **P** throughout, built on the **C** rules they cite.

Vocabulary, used in code, docs and reviews: **Record page**, **List page**, **Manage page**, **Identity bar**, **Figures strip**, **Figure**, **Main column**, **Details aside**, **Related list**. It is layout vocabulary: keep it out of the app's domain glossary.

## Choosing a template

1. **A record with figures or related records gets a Record page;** its resource's list is a List page. A `ViewRecord` built with `RecordLayout::make()` (below). **P**.
2. **A flat reference list gets a Manage page.** A few short fields, no uploads, no related records: one `ManageRecords` list where the row opens the Edit slide-over and Add opens a slide-over, with no Create, Edit or View page. **P** (`layout-and-density.md` rule 9).
3. **Pages extend Filament's own classes** (`ViewRecord`, `ListRecords`, `ManageRecords`); the templates add no base classes. **P**.

## Record page

The page, top to bottom: the **Identity bar** as the page heading, the **Figures strip**, then the **Main column** (two thirds) beside the **Details aside** (one third) from `lg`, one column below.

4. **The Identity bar names the record.** Its identifier, its name and its status as a badge with its word, in place of the heading and breadcrumbs, with no subheading. The page's one primary action sits at its right end and every other header action goes in one `ActionGroup` ("…"), chosen per role and record state. **P** (`layout-and-density.md` rules 5 and 6).
5. **The Figures strip holds 3 to 5 Figures, or the page has none.** A Figure is a label over a large value with at most one caption line of data (such as "ex-VAT · 3 lines · 1 unpriced"), never a sentence, all three aligned to the end so the label sits over its value and numbers line up. From `lg` the strip has as many columns as Figures the viewer can see, never fewer than 3, so the row is always full and a lone Figure keeps a normal width. More candidates than five: move the less important ones into the Details aside. The count is of declared Figures: a record with fewer than 3 figures worth a strip declares none and the strip is left out, and the builder refuses 1–2 or more than 5. Figures are infolist entries, never the Stats widget, which belongs on dashboards. **P** (`layout-and-density.md` rules 11 and 27, `numbers-and-dates.md`).
6. **The Main column is flat.** The record's content, then its widgets and Related lists, most used first; a state that needs action comes first of all. Regions are separated by spacing and at most a hairline, never boxed: a Section inside it is `->contained(false)`. A region gets a heading only when its content doesn't explain itself (a row of photos needs none). An unlabelled region with no value is hidden, since a lone "—" with no label reads as nothing. **P** (`layout-and-density.md` rule 18).
7. **The Details aside is the only box.** A compact Section headed "Details" with inline labels, holding the facts that aren't Figures (identifiers, classification, contacts, owner, dates). It is sticky, so it stays in view while a long Related list scrolls. **P** (`layout-and-density.md` rules 10 and 22).
8. **Related lists sit in the Main column.** A relation manager placed with `getRelationManagersContentComponent()`, never in a tab beside the details. Its heading, search and one action share one row: the heading is the table heading, the action a table header action, and no Section wraps it. When the page's primary action already does the list's job, the list has no action of its own. Search only when the list can run past 25 rows, and page controls only when it does: a list that fits one page shows none. The first Related list loads with the page (`protected static bool $isLazy = false;`) rather than as a skeleton. Every Related list has an empty state with the next step, and a "nothing matches" state only when it has search or filters. **P** (`layout-and-density.md` rule 14, `copy-and-feedback.md` rule 10).

## List page and Manage page

9. **A List page starts with the work.** No heading: `getHeading()` returns null, since the active navigation item names the page. Status tabs with counts come first, then one toolbar row with search, the filter dropdown, the column toggle and the primary action, then the rows. Put the primary action in the table's `toolbarActions()`: `getHeaderActions()` and the table's `headerActions()` each add a row above the toolbar. Filters stay in Filament's default dropdown. Every List page has 25 rows per page and both empty states. **P** (`tables-and-finding.md` rules 3 and 6, `layout-and-density.md` rules 23 and 24, `copy-and-feedback.md` rule 10).
10. **A Manage page is a List page whose row opens the Edit slide-over.** Keep `edit` in `recordActions()` and set no `recordUrl()`, so the row click opens it. Add opens in a slide-over too. **P**.
11. **A dashboard follows the List page:** no heading and a flat surface. **P**.

## Building blocks

Install once per app:

1. Require `josephdlmd/filament-ux` in `require` (pages use its classes at runtime).
2. Import the package CSS into the panel's custom theme, then rebuild the assets. It holds the one rule that makes the Details aside sticky.
   ```css
   @import '../../../../vendor/josephdlmd/filament-ux/resources/css/layout.css';
   ```
3. Turn breadcrumbs off on the panel with `->breadcrumbs(false)`. `InteractsWithRecordLayout` also clears them on each Record page.

A Record page with `RecordLayout::make()`:

```php
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;
use Josephdlmd\FilamentUx\Layout\Concerns\InteractsWithRecordLayout;
use Josephdlmd\FilamentUx\Layout\IdentityBar;
use Josephdlmd\FilamentUx\Layout\RecordLayout;

class ViewOrder extends ViewRecord
{
    use InteractsWithRecordLayout;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SendOrderAction::make(),
            ActionGroup::make([EditAction::make(), DeleteAction::make()]),
        ];
    }

    protected function recordLayout(RecordLayout $layout): RecordLayout
    {
        return $layout
            ->identity(IdentityBar::make($this->record->number, $this->record->customer->name)
                ->status($this->record->status->getLabel(), $this->record->status->getColor()))
            ->figures(OrderInfolist::figures())
            ->main([
                ...OrderInfolist::main(),
                $this->getRelationManagersContentComponent(),
            ])
            ->aside(OrderInfolist::details());
    }
}
```

- **`RecordLayout`**: `make()`, then the slots `identity(IdentityBar)`, `figures(array)`, `main(array)` and `aside(array)`. `toSchema(Schema)` returns the Figures strip, then the Main column beside the Details aside; the order and the 2/3 and 1/3 proportions are fixed, with no options.
- **`InteractsWithRecordLayout`** (on a `ViewRecord`): the page implements `recordLayout()`; the trait renders the layout as the page's infolist, makes the Identity bar the heading (`getHeading()`), returns no breadcrumbs, and renders only the layout as the page content. So place the Related lists (`getRelationManagersContentComponent()`) and record widgets (`getWidgetsSchemaComponents([...])`) in `main()` yourself: nothing is appended below the layout. `getTitle()` is untouched, so the browser tab keeps the record title.
- **`IdentityBar::make($identifier, $name)->status($label, $color)`**: an `Htmlable` heading; a null label shows no badge. The badge is Filament's own.
- **`Figure::make($name)`**: a `TextEntry` subclass, large and semibold, its label, value and caption aligned to the end (the label stays the entry's term for screen readers; a copy shows above the value). Chain entry methods as usual (`->label()`, `->money()`, `->placeholder('—')`, `->visible()`), and give the caption with `->caption()`: text, prime components such as a badge, or a closure giving either; blank shows none.
- **`RecordLayout::asideActions($actions)`**: actions on the Details aside's heading row, for what acts on the details it holds (Edit), rather than in the Identity bar.
- **`FiguresStrip::make($figures)`**: a `Grid` of 2 columns, and from `lg` one per visible Figure, at least 3; throws `InvalidArgumentException` outside 3 to 5 Figures. `RecordLayout` leaves it out when the page declares no Figures.
- **`MainColumn::make($components)`**: a `Group` two thirds wide from `lg`.
- **`DetailsAside::make($components)`**: a compact `Section` "Details" with inline labels, one third wide from `lg`, carrying the `fux-details-aside` class that the package CSS makes sticky.

Use the blocks on their own for an unusual page: a `FiguresStrip` on a custom page, or a `Grid::make(['lg' => 3])` of `MainColumn` and `DetailsAside` without Figures. Keep a resource's parts as static methods on its Infolist class (`figures()`, `main()`, `details()`) so tests and other pages can reuse them.

Tests find the regions by what a user sees: the text order (a Figure's label, then Main column content, then "Details") and the one `fux-details-aside` element. Layout components carry no keys, so entries keep their own names for `assertSchemaComponentHidden()` and similar.
