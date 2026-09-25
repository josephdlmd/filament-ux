# Tables, finding records and bulk work

The list page is where a dense business tool spends most of its time. Tags **C**, **S**, **P** as defined in `SKILL.md`. Layout and density of the table itself are in `layout-and-density.md`.

## Finding

1. **Find a record by any identifier people quote.** Code, name, reference, brand; for people and companies also contact and phone. Table search and global search (`getGloballySearchableAttributes()`, `getGlobalSearchResultDetails()`) cover the same fields, and global search has `globalSearchKeyBindings(['ctrl+k', 'command+k'])`. **C** (NN/g, Filament; Mobbin).
2. **One search box per table.** A scope picker is fine if "All" is the default. **S** (NN/g, Carbon).
3. **Work queues are status tabs with counts.** A list with to-do states gets `getTabs()` with `badge()` counts ("To send 4", "Overdue 2") in place of toggle filters, and a dashboard count links to its tab. Define each to-do once (label, query scope, permission, count), for example in an enum, and build the dashboard tile, the tab badge and the tab rows from it so they can never disagree. **P** (Mobbin: Stripe, Shopify, Linear).
4. **Lists remember their state.** `persistFiltersInSession()`, `persistSearchInSession()`, `persistSortInSession()` on working lists; a link with filters or a tab in its URL overrides what was remembered. **C** (NN/g heuristic 7, Filament). Named saved views are the common SaaS alternative; Filament's list tabs cover the fixed ones.
5. **Filters go from general to specific.** **C** (NN/g filter categories).
6. **Filter timing follows `profile.data_volume`.** Small, fast tables filter live (`deferFilters(false)`) with the active filters shown as removable indicators; large or slow tables keep Filament's Apply button. Up to about six filters in the dropdown; more, or a QueryBuilder, go `FiltersLayout::AboveContentCollapsible`. **C** (NN/g applying filters, Carbon; live filtering is what current SaaS does on Mobbin).
7. **QueryBuilder only for trained users who need OR or nested conditions.** Everyone else gets named filters. Scope restricted rows in the query, never with `options()`. **P**.
8. **Rank candidates before cutting the list.** Suggestion lists show the closest first and are cut only after ranking. **C** (NN/g 2025, Carbon).
9. **Exact matches stay in code.** A duplicate check, an identifier or a uniqueness rule is never decided by an AI judgment; semantic judgments may only suggest or rank. **P**.
10. **A warning leads to the next step.** A "possible duplicate" or "already exists" warning offers the action on that record, not only a link. **P**.

## Speed

11. **Every daily action has a keyboard shortcut** (`keyBindings()`), named in its tooltip or printed on the button. **C** (NN/g accelerators; Stripe, Linear on Mobbin).
12. **Repeating tasks keep the form open.** Offer "then add another" or "then next" where people enter several in a row, keeping the values that repeat. **P** (after NN/g heuristic 7).

## Columns and rows

13. **Working lists are tables.** Cards (`contentGrid()`) only for image-led browsing; a second line goes in `description()`. **C** (NN/g, GOV.UK; even image-heavy SaaS lists use tables on Mobbin).
14. **Inline-edit columns only for one frequent, independent, reversible field.** Each sets `rules()`, is `disabled()` by the same policy as Edit, and logs its change; never money or a state change with side effects. `profile.users = occasional`: none. **C** (PatternFly, Atlassian, Filament).
15. **No deprecated columns.** `TextColumn->badge()`, `IconColumn->boolean()`, badge `TextColumn` with `limitList()`. **S**.
16. **`ColumnGroup` only for columns that share a qualifier** (Billing: Name, City). **P**.

## Bulk actions

17. **Bulk friction scales with reversibility.** Irreversible: confirm with the count and knock-on losses. Reversible: no confirmation, a notification with the count and Undo. **C** (NN/g, Pajamas; Mobbin).
18. **Bulk actions honour per-record policy.** `authorizeIndividualRecords()` whenever `delete()` or `update()` is stricter than `deleteAny()`; Filament otherwise checks only `deleteAny()`. Unsafe rows are unselectable with `checkIfRecordIsSelectableUsing()`. **S** (Filament). Security: test it.
19. **Cap destructive selections.** `selectCurrentPageOnly()` or `maxSelectableRecords()`; `chunkSelectedRecords()` for heavy work. **P**.

## Pages of results

20. **Numbered pagination, never infinite scroll.** Show "x–y of N". `PaginationMode::Simple`/`Cursor` only for logs or huge tables. `poll()` only on lists others change while you act, at 30 seconds or more. Group rows only by a stable parent, `collapsible()`, with pages large enough not to split a group. **C** (GOV.UK, Pajamas, NN/g; Mobbin).

## Import and export

21. **Imports give failures back.** Every `ImportColumn` has `example()` (so the template is useful), `rules()` with messages in the copy rules' voice, and `requiredMapping()` on identifiers. `resolveRecord()` keys on the human identifier; `maxRows()` is set; the completion notification gives imported and failed counts and is `persistent()` with the failed-rows file when any fail; `preventFormulaInjection()` for outside files. Keep an import history. **S** (Filament) plus **P**; Mobbin agrees.
22. **Exports match the screen.** `ExportAction` exports the filtered query and `ExportBulkAction` the selection, with `enableVisibleTableColumnsByDefault()`, glossary headers, the list's authorisation in `modifyQueryUsing()`, and `preventFormulaInjection()`. `ReplicateAction` excludes identifiers and opens the copy for editing. **S** plus **P**.
