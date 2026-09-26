## Filament UX (josephdlmd/filament-ux)

This app is an information-dense business tool built with native Filament components. Before building or reviewing any Filament screen, activate the `filament-ux` skill: it reads the app's `.ai/ux-profile.md` and holds the full sourced rules. These always apply:

- **Copy:** sentence case with glossary terms capitalised; buttons are verbs that answer the popup's title; examples go in one-line `helperText()`, never placeholders; errors say what to do ("Enter the due date", "Quantity must be 1 or more") using the field's label.
- **Confirm only losses:** `requiresConfirmation()` only for what can't be undone, with the record or count in the title and the consequence in the description. Reversible actions notify with Undo instead.
- **Feedback:** every save sends a short notification naming the record and the outcome; every list has an empty state with a next step and a "nothing matches" state with Clear filters.
- **Layout:** full width; every screen is a Record page, List page or Manage page; record pages are built with `RecordLayout::make()`: an Identity bar, a Figures strip of 3 to 5 Figures, then a flat Main column beside a sticky Details aside; List pages have no heading and one toolbar row; no breadcrumbs; one primary action per screen, the rest under "…"; every add and edit in a slide-over, centred modals only for losses and one-value actions; one-column forms with no boxed Sections.
- **Prose:** text only in empty states, loss confirmations and one-line format examples; each fact shown once, in its strongest form.
- **Tables:** identifier first, at most 8 visible columns, 25 rows per page, one inline row action with the row opening the record; work queues are status tabs with counts.
- **Inputs:** Radio or ToggleButtons up to 5 options, Select up to 15, searchable Select beyond; Checkbox for a yes/no saved with the form.
- **Colour:** one colour per meaning (success done, warning to-do, danger only for losses and failures, gray neutral), always with a word or icon; tooltips only repeat what is visible elsewhere.
- **Numbers:** right-aligned, one money formatter, decimals kept, negatives by sign not colour; timestamps converted to the display timezone.
- **Bulk actions:** `authorizeIndividualRecords()` whenever deleting or updating one record is stricter than doing it to any.
- **Accessibility floor:** targets at least 24px and nothing lost at 200% zoom; density never goes below it.
- **AI suggestions:** code decides identifiers, money and permissions; a model judgment only ranks or proposes, shown in words with its reason as visible text, with one-click Accept, Dismiss and Undo, and the page works when the service is down.
