# Copy and feedback

Words on buttons, labels, hints, errors, confirmations and notifications. Tags **C**, **S**, **P** as defined in `SKILL.md`.

## 1. One word per thing
Every label, heading, filter and notification uses the term from `profile.glossary`, and a field has the same label on every screen. **S** (NN/g heuristic 4); the terms are the app's.

## 2. Sentence case, glossary terms capitalised
Capitalise the first word and glossary terms only ("Add Order", "Payments for this Invoice"). No full stop or trailing colon on buttons, labels or headings; full sentences in tooltips, errors and popup text end with a full stop. **C** (Microsoft, GOV.UK, Mailchimp; confirmed in current SaaS on Mobbin); capitalising glossary terms is **P**.

## 3. Buttons are verbs, and a popup's buttons answer its title
A button says what happens in a couple of words; never "OK", "Yes" or a bare noun. The create verb is `profile.create_verb` everywhere, set once through `lang/vendor/filament-*` overrides rather than per action. **C**; the verb choice is **P** ("Create" is more common in current SaaS, "Add" is also fine).

## 4. No placeholders as hints; shape the input before showing an example
A value with a fixed shape (a phone number, a tax number, a code) gets an input mask that writes it as it is typed and refuses what can't belong (`inputs.md` rule 6), and no example text. Where no mask can shape the answer, an example goes in `helperText()` as one short "Such as …" fragment; never "e.g.", "i.e." or "etc". **C** (GOV.UK, NN/g). Placeholder hints are common in current apps; the guidance is better supported. Preferring a mask to an example is **P**.

## 5. Helper text is one short line
A one-line format example with no full stop, only where the label isn't enough and no mask can shape the input, never repeating a prefix or suffix (rule 15). **C** (GOV.UK, NN/g).

## 6. Errors say what to do
"Enter [field]", "Select [field]" or "[Field] must be …", using the field's own label; name and link any conflicting record; keep what people typed when a custom action refuses to save. Never "invalid", "incorrect", "illegal", "forbidden", "sorry", "is required", or "please" in a field error; "please" only when the system is at fault. **C** (GOV.UK, NN/g, Microsoft). "Please enter a valid …" is common in current apps; the guidance is better supported.

## 7. Prevent before refusing
Hide an action that can't succeed. When people need to know it exists, disable it and state the reason as **visible text** next to it (a Callout, helper text or description), not only in a tooltip. Warn inline before submit rather than refusing after a confirmation. Don't disable Submit until the form is valid; validate on submit. **C** (NN/g, GOV.UK; every current app on Mobbin shows the reason as text).

## 8. Confirm only what can't be undone
`requiresConfirmation()` only for deletes and other losses. The popup title names the record or the count ("Delete Order 1042?", "Delete 3 Orders?"), the description states the consequence and any knock-on losses ("and their 12 Payments"), and the submit button repeats the verb ("Delete", or "Delete Orders" when the title doesn't name them). Reversible actions skip the confirmation and their notification carries Undo; a reversible action with a big effect gets a gray button plus a confirmation stating the effect. **C** (NN/g, GOV.UK, Filament, Pajamas; Mobbin).

## 9. Every save says what happened
A short success notification names the record and the outcome ("Order 1042 sent") and offers View or Undo where useful. Bulk results give counts ("12 Orders archived, 2 skipped: already paid"). Only notifications that need action are `persistent()`. **S** (NN/g heuristic 1) plus **P**; confirmed on Mobbin.

## 10. Every list has two empty states
Nothing yet: `emptyStateHeading`, `emptyStateDescription` and an `emptyStateActions` next step. Nothing matches: when filters or search empty the list, say so and offer Clear filters. **C** (NN/g, Carbon, Atlassian; Mobbin).

## 11. Show names next to codes
Show names beside codes and letters, and show a derived figure's working as visible text on the record's page (a tooltip may repeat it). **S** (NN/g heuristic 6); see colour-and-accessibility rule 6.

## 12. Plain words
"Select", not "click"; "and", not "&" (except in names); spell out an abbreviation that isn't a glossary term the first time a screen uses it; active voice in full sentences. **C** (GOV.UK, Microsoft, Mailchimp).

## 13. Unsaved work is never lost silently
Turn on `unsavedChangesAlerts()`. On long edit pages, show that there are unsaved changes next to Save. **P** (NN/g heuristic 5; Shopify, Klaviyo on Mobbin).

## 14. Blank values say so
A column or entry with no value shows `->placeholder('—')`, or a word that means something ("Never", "Not set") where a dash would be ambiguous, so an empty cell never looks like a loading or broken one. **P**.

## 15. Prose only where the screen can't show it
Text appears in empty states (the next step), in confirmations of losses (the consequence), in one-line format examples as `helperText()` where no mask can shape the input, and as the visible reason for a disabled action (rule 7) or a suggestion (`ai-suggestions.md` rule 4). Everything else is shown, not stated: no Section descriptions, page subheadings or hints, and no helper text or description that explains what a label, badge, grouping or order can show. Replace such a sentence with a better label, grouping or badge. A money Figure keeps its working as one caption line of data (rule 11). **P** (NN/g heuristic 8).

## 16. One mark per fact
Each fact appears once on a screen, in its strongest form: a badge over a sentence, an icon over a word when the icon is unambiguous. A status shown as a badge carries no sentence restating it, and the Identity bar's status isn't repeated in the Details aside. **P** (NN/g heuristic 8).
