# AI suggestions

How to show an AI judgment (a TypeSafe yes/no probability, choice, score or confidence, or any similar model output) in a Filament screen. Code stays in control: the judgment ranks or proposes, a person or plain code decides. Tags **C**, **S**, **P** as defined in `SKILL.md`.

## Confidence bands

Every rule below works in three **bands**. Code maps each judgment to one:

- **High**: act on it as a suggestion
- **Uncertain**: ask the person to check
- **Low** (including a failed or timed-out call): say nothing

For a Choice or Score, band on `confidence`, with a floor near 0.5 and a higher bar where a mistake costs more. For a yes/no (Noul), which has no confidence, band the probability itself (for example 0.30–0.70 is Uncertain). Set the cut points per decision from logged outcomes (rule 12), never from a demo.

## Rules

1. **Code decides; the judgment only ranks or proposes.** Exact checks (identifiers, uniqueness, totals, permissions) run in code first. A judgment never writes money, identifiers, permissions or status. **C** (TypeSafe, PAIR, Pajamas; `tables-and-finding.md` rule 9).
2. **Speak only when it changes the next step.** High: show the suggestion. Uncertain: show it as "Check". Low: show nothing and keep the code's own order. **C** (Microsoft HAX guidelines 3, 4 and 10; PAIR).
3. **Confidence is words tied to an action, never a percentage.** "Likely the same Customer" (High), "Might be the same Customer: compare" (Uncertain). The order of a list carries the rest. **C** (PAIR, Apple HIG, TypeSafe; Mobbin).
4. **The reason sits beside the suggestion as plain text.** Say which fields agree and which differ (from companion yes/no checks), in neutral wording with no "I think". Never tooltip-only. **C** (HAX 11, PAIR, Apple, NN/g; `colour-and-accessibility.md` rule 6). Mechanism: `description()`, `helperText()`, a `Text` component.
5. **Label suggestions with a word.** A "Suggested" badge on each item and one line per screen: "Suggested automatically. Check before using." No bare sparkle icon and no "AI" in button labels. **C** (Carbon, Pajamas, NN/g).
6. **One-click Accept and Dismiss, and Accept can be undone.** Accepting fills a field or links a record; it never saves silently. High puts Accept first; Uncertain puts Compare first. Mechanism: `Callout` `actions()`, `hintAction()`, and an Undo action on the notification. **C** (HAX 8 and 9, Pajamas, Carbon; Mobbin).
7. **"Same record?" opens a side-by-side comparison.** Name the differing fields and offer the action on the existing record. Merging is a separate step that `requiresConfirmation()` and says what will be lost. High shows it as a warning, Uncertain as info. **C** (every CRM sampled on Mobbin compares before merging; TypeSafe entity-alignment cookbook).
8. **Pending suggestions are a "Suggested" status tab with a count** (`getTabs()` with `badge()`), holding High and Uncertain only. **P** (`tables-and-finding.md` rule 3).
9. **Bulk accept covers High rows only.** Uncertain rows can't be selected (`checkIfRecordIsSelectableUsing()`); the selection is capped (`maxSelectableRecords()`); money and identifiers are never bulk-accepted; the result gives a count and Undo. **P** (`tables-and-finding.md` rules 17–19).
10. **Auto-apply is opt-in per kind of suggestion,** only above the High bar, only for low-stakes fields, always reversible, with a history of what was applied. Off by default. **C** (HAX 17, Pajamas; Google Ads, Canny on Mobbin).
11. **No results shows "Closest matches" and an Add action.** Up to five for High and Uncertain; Low shows Add only. Never rewrite the person's search. **C** (NN/g; Mobbin).
12. **Log every judgment with its decision:** input hash, model returned, probabilities, confidence, band, whether it was shown, user, outcome and time, including Low ones that were hidden. The log is how cut points get tuned. **P** (TypeSafe, PAIR, Atlassian).
13. **Feedback is the Accept or Dismiss itself,** plus an optional reason on Dismiss from a short list (`ToggleButtons`). No thumbs on every item. **C** (PAIR, HAX 15).
14. **The page works without the service.** Judgments load lazily and time out fast (`Livewire::make()->lazy()`, `deferLoading()`); saving never waits on them; a failure counts as Low. Test it with the client faked to throw. **C** (PAIR, Pajamas, HAX 10).
