# Filament traps

Filament 5 behaviours that quietly break the rules in the other references. Each was hit in a real app; each has the symptom, the cause, and the fix. Check this file whenever the change touches the area named in a heading.

## Bulk actions

- **Per-record permissions are skipped.** Bulk delete, restore and force-delete check `deleteAny()` (or `restoreAny()`, `forceDeleteAny()`) once for the whole batch. A rule in `delete()` such as "nobody deletes their own account" never runs. Fix: `->authorizeIndividualRecords('delete')`, and make rows that must never be picked unselectable with `checkIfRecordIsSelectableUsing()`. Test it: select a forbidden record with others and assert only the others go. (`tables-and-finding.md` rule 18)
- **"Select all" spans every page.** Fix: `selectCurrentPageOnly()` on the table; rows ticked one by one across pages still add up, and the confirmation must state the true count. (`tables-and-finding.md` rule 19)
- **Default wording ignores the count.** Build the heading, submit label and notification from `getTotalSelectedRecordsCount()`: "Delete 1 Product?", "Delete Product", "Product deleted" for one; "Delete 3 Products?", "Products deleted: 3" for several. Set it once with `DeleteBulkAction::configureUsing()`. (`copy-and-feedback.md` rule 8)

## Tables and lists

- **Removing the View row button changes where the row goes.** Filament takes the row URL from the View action first, then Edit. Drop View and rows open Edit. Fix: set `->recordUrl(fn ($record) => Resource::getUrl('view', ['record' => $record]))` explicitly and test the row's URL. (`layout-and-density.md` rule 24)
- **A tab in the URL doesn't clear remembered filters.** With `persistFiltersInSession()`, a dashboard link to `?tab=stale` still applies yesterday's remembered filters and search, hiding rows the count promised. Fix: in the list page's `mount()`, when the URL carries a to-do tab, reset the remembered filters and search (keep the sort; it hides no rows). A link with filters in its URL already wins on its own. (`tables-and-finding.md` rules 3–4)
- **Grouped lists split groups across pages.** 25 rows per page cuts a group in half. Give grouped lists a larger page (`defaultPaginationPageOption(100)`). (`tables-and-finding.md` rule 20)

## Dates and times

- **`dateTooltip()` and `date()` ignore the panel timezone** unless the column is a `dateTime()` column; otherwise they format in `app.timezone` (UTC), so anything before 08:00 Manila shows yesterday. Fix: pass `timezone: config('app.display_timezone')` explicitly. (`numbers-and-dates.md` rule 10)
- **`sinceTooltip()` on a date-only column counts from UTC now**, so "age" can read a day short between midnight and the UTC offset. The visible date is right; accept it or compute the age in code.
- **PHP abbreviates some zones misleadingly** (Asia/Manila → "PST", read as US Pacific). Put the zone once in the column label ("When (Manila time)") instead of on every value.

## Global search

- **`globalSearchFieldKeyBindingSuffix()` shows only the first key binding**, so `['ctrl+k', 'command+k']` shows "⌃K" on a Mac. Fix: `globalSearchFieldSuffix(fn () => match (Platform::detect()) { Platform::Mac => '⌘K', Platform::Windows, Platform::Linux => 'CTRL+K', default => null })`. (`tables-and-finding.md` rule 1)
- **Setting `$recordTitleAttribute` turns global search on** for that resource. Turn it off with `$isGloballySearchable = false` where nobody looks records up.

## Forms

- **`TextInput::tel()` adds a pattern that refuses letters**, so "(02) 8123 4567 loc. 12" fails. Keep the phone keypad and today's accepted values with `->tel()->regex(null)`, or keep the pattern and say what to enter. (`inputs.md` rule 6)
- **Upload limits apply to new files only**, but `maxFiles()` counts files already stored: a record over the new limit can't be saved from Edit until some are removed. Check existing data before lowering a limit. (`inputs.md` rule 8)
- **Swapping a Select for ToggleButtons changes the required-field message** from "Select …" to "Enter …". Configure ToggleButtons (and Radio) with the same "Select [field]" message as selects. (`copy-and-feedback.md` rule 6)

## Charts

- **`ChartWidget::isEmpty()` is only true for an empty data array.** Charts that always return labels never show their empty state. Override `isEmpty()` to check what was counted. (`dashboards-and-charts.md` rule 10)
- **A chart's description is also its canvas `aria-label`.** Long descriptions (every group named) are read out in full; keep that in mind when choosing between a long description and axis labels. (`dashboards-and-charts.md` rule 7)

## Panel

- **An expired session on a Livewire request shows the browser's "This page has expired" `confirm()`**, not the 419 page. Fix: a `PanelsRenderHook::SCRIPTS_AFTER` view that registers `Livewire.interceptRequest`, calls `preventDefault()` on a 419 and shows the app's 419 page. Test the hook is on the page; check the swap once in a browser. (`auth-and-panel.md` rule 10)
- **Code that relies on admin-editable records needs a hidden stable key.** Looking up a Tag Type, status or setting by its name breaks silently when an Admin renames it. Add a nullable unique `key` column, fill it in the migration for existing rows, look records up by key, and let the seeder find them by key so a renamed row isn't duplicated.
