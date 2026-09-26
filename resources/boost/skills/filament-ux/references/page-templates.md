# Page templates

Every screen in the panel is one of three templates: a **Record page**, a **List page** or a **Manage page**. Each reads as one designed screen rather than a stack of components, and two apps built on them feel the same. The Record page is assembled with this package's building blocks (`Josephdlmd\FilamentUx\Layout`), which use only Filament's own schema components, page methods and design tokens, so they look right under any theme. Tags **C**, **S**, **P** as defined in `SKILL.md`; the templates are **P** throughout, built on the **C** rules they cite.

Vocabulary, used in code, docs and reviews: **Record page**, **List page**, **Manage page**, **Identity bar**, **Figures strip**, **Figure**, **Main column**, **Details aside**, **Related list**. It is layout vocabulary: keep it out of the app's domain glossary.

## Choosing a template

1. **A record with figures or related records gets a Record page;** its resource's list is a List page. A `ViewRecord` built with `RecordLayout::make()` (below). **P**.
2. **A flat reference list gets a Manage page.** A few short fields, no uploads, no related records: one `ManageRecords` list where the row opens the Edit slide-over and Add opens a slide-over, with no Create, Edit or View page. **P** (`layout-and-density.md` rule 9).
3. **Pages extend Filament's own classes** (`ViewRecord`, `ListRecords`, `ManageRecords`); the templates add no base classes. **P**.

## Record page

The page, top to bottom: the **Identity bar** as the page heading, the **Figures strip**, then the **Main column** (two thirds) beside the **Details aside** (one third) from `lg`, one column below.

4. **The Identity bar names the record,** unless the page's one Related list names it in its heading (rule 8). Its identifier, its name and its status as a badge with its word, in place of the heading and breadcrumbs, with no subheading. The page's one primary action sits at its right end and every other header action goes in one `ActionGroup` ("…"), chosen per role and record state. **P** (`layout-and-density.md` rules 5 and 6).
5. **The Figures strip holds 3 to 5 Figures, or the page has none.** A Figure is a gray regular-weight label over a large value with at most one small gray caption line of data (such as "ex-VAT · 3 lines · 1 unpriced"), never a sentence, all three aligned to the end so the label sits over its value and numbers line up. From `lg` the strip has as many columns as Figures the viewer can see, never fewer than 3, so the row is always full and a lone Figure keeps a normal width. More candidates than five: move the less important ones into the Details aside. The count is of declared Figures: a record with fewer than 3 figures worth a strip declares none and the strip is left out, and the builder refuses 1–2 or more than 5. Figures are infolist entries, never the Stats widget, which belongs on dashboards. **P** (`layout-and-density.md` rules 11 and 27, `numbers-and-dates.md`).
6. **The Main column is flat.** The record's content, then its widgets and Related lists, most used first; a state that needs action comes first of all. Facts of any length that describe the record's work rather than how to reach or deal with it (what it deals in, its notes) may follow its Related list here as `DetailsGroup`s, leaving the aside to short facts. Regions are separated by spacing and at most a hairline, never boxed: a Section inside it is `->contained(false)`. A region gets a heading only when its content doesn't explain itself (a row of photos needs none). An unlabelled region with no value is hidden, since a lone "—" with no label reads as nothing. **P** (`layout-and-density.md` rule 18).
7. **The Details aside is the only box.** A compact Section headed "Details" with inline labels, holding the facts that aren't Figures (identifiers, classification, contacts, owner, dates). It is sticky, so it stays in view while a long Related list scrolls. **P** (`layout-and-density.md` rules 10 and 22).
   - **Grouped when long.** More than about six facts read as a few groups under small headings, each set off by a hairline (`DetailsGroup`), in the order the record's form asks for them, such as Contact, Terms, Notes. Notes run the full width with no label of their own.
   - **Acted on where it stands.** A contact detail is usable in place (`ContactEntry`): a phone number offers Call (and Viber, where it is a mobile and the users message that way), an email opens a new email, an address opens a search on Google Maps (and Waze). A missing detail reads "—", as it is worth filling in.
   - **Its action on its heading.** An action on the details themselves, Edit, sits on the aside's heading row (`asideActions()`), not in the Identity bar.
   - **One type scale, set by the package CSS.** The record's name, where a Related list heading names it, and "Details" alike at 16px, Filament's heading size, as the two cards sit level; a group heading 14px semibold; values in the text colour, links included (underlined on hover), over gray regular-weight labels; a value's small links (Call, Google Maps) and caption 12px gray, lighter than the value. Each level is smaller or quieter than the one it belongs to, so the eye goes card, group, value, and reads a label only when looking for one.
   - **A to-do that is a fact about the record** (a missing document) may sit in the aside instead of first in the Main column, since the sticky aside keeps it in view, leaving the Main column to the record's main work.
8. **Related lists sit in the Main column.** A relation manager placed with `getRelationManagersContentComponent()`, never in a tab beside the details. When it is the page's one Related list, the page may leave out the Identity bar and let that list's heading name the record instead (`->heading($record->name)`), so the page opens on two cards whose headings line up: the record's name over its list, "Details" over its facts. Status tabs on it (All, Active, Stale) become one button group at the right end of its search row, each labelled with its count (`HasViewChoice`), not a row of tabs above it. Otherwise its heading, search and one action share one row: the heading is the table heading, the action a table header action, and no Section wraps it. When the page's primary action already does the list's job, the list has no action of its own. Search only when the list can run past 25 rows, and page controls only when it does: a list that fits one page shows none. The first Related list loads with the page (`protected static bool $isLazy = false;`) rather than as a skeleton. Every Related list has an empty state with the next step, and a "nothing matches" state only when it has search or filters. **P** (`layout-and-density.md` rule 14, `copy-and-feedback.md` rule 10).

## List page and Manage page

9. **A List page starts with the work.** No heading: `getHeading()` returns null, since the active navigation item names the page. Status tabs with counts come first, then one toolbar row, search, the filter dropdown and the column toggle on the left and the primary action at its right end, then the rows. Put the primary action in the table's `toolbarActions()`, which the package CSS moves to the right end of that row: `getHeaderActions()` and the table's `headerActions()` each add a row above the toolbar. Show a column toggle only where a column is worth hiding; decide what a list shows rather than leave it to each viewer. Filters stay in Filament's default dropdown. Every List page has 25 rows per page and both empty states. **P** (`tables-and-finding.md` rules 3 and 6, `layout-and-density.md` rules 23 and 24, `copy-and-feedback.md` rule 10).
10. **A Manage page is a List page whose row opens the Edit slide-over.** Keep `edit` in `recordActions()` and set no `recordUrl()`, so the row click opens it. Add opens in a slide-over too. **P**.
11. **A dashboard follows the List page:** no heading and a flat surface. **P**.

## Forms

12. **A form reads as its record's Details read.** The same fields in the same order and groups, so what someone fills in is where they will later look for it. **P** (`layout-and-density.md` rule 21).
13. **Labels beside their fields, close.** A slide-over form may use inline labels (`$schema->inlineLabel()`); the package CSS gives the label column just enough width for a two-word label, not a third of the form. **P**.
14. **Each field as wide as its answer.** A phone number or a country short (`max-w-3xs`), a person's name or an email medium (`max-w-sm`), a name wider (`max-w-md`), an address, a pick list of many or notes the full width; set on the field with `->extraAttributes(['class' => …])`. A width that fits the answer tells people what is expected. **P** (GOV.UK text input widths).
15. **A choice shows what is chosen in the primary colour,** whichever option it is (`ToggleButtons::colors()`). Amber and red mark to-dos and losses on the record, not a choice being made. **P** (`colour-and-accessibility.md`).
16. **Save on the right.** A slide-over's footer actions sit at its right end, the submit action rightmost, where the eye finishes the form: in a service provider, `Action::configureUsing(fn (Action $action) => $action->modalFooterActionsAlignment(fn (Action $action) => $action->isModalSlideOver() ? Alignment::End : null))`. A centred modal keeps Filament's alignment. **P**.
17. **Checked and stored in one form.** A phone number is checked to be real for its country and kind, and stored in international form; a structured identifier (a tax number) is stored as written on its source document, however typed; a fixed set of standard values (payment terms) is a pick list, not free text. **P**.

## Building blocks

Install once per app:

1. Require `josephdlmd/filament-ux` in `require` (pages use its classes at runtime).
2. Import the package CSS into the panel's custom theme, then rebuild the assets. It makes the Details aside sticky, moves a list's own actions to the right end of its toolbar, and keeps inline form labels close to their fields.
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
- **`DetailsGroup::make($heading, $components, first: false)`**: a group of the Details aside under a small heading, with a hairline above it unless `first`.
- **`ContactEntry::phone($name, region: 'PH', viber: true)`**, **`::email($name)`**, **`::address($name, country: fn ($record) => …, waze: true)`**: `TextEntry`s whose Call, Viber, email and map links sit under the value, each shown only when there is a value; `ContactEntry::e164()` and `::readable()` format a number as dialled and as read. Turn Viber and Waze off where the users don't use them.
- **`HasViewChoice`** (on a `RelationManager` with `getTabs()`): drops the tabs row and gives `viewChoice()`, the tabs as one button group for the table's `toolbarActions()`, each action named `show` and the tab's key (`showStale`).
- **`DetailsAside::make($components)`**: a compact `Section` "Details" with inline labels, one third wide from `lg`, carrying the `fux-details-aside` class that the package CSS makes sticky.

Use the blocks on their own for an unusual page: a `FiguresStrip` on a custom page, or a `Grid::make(['lg' => 3])` of `MainColumn` and `DetailsAside` without Figures. Keep a resource's parts as static methods on its Infolist class (`figures()`, `main()`, `details()`) so tests and other pages can reuse them.

Tests find the regions by what a user sees: the text order (a Figure's label, then Main column content, then "Details") and the one `fux-details-aside` element. Layout components carry no keys, so entries keep their own names for `assertSchemaComponentHidden()` and similar.
